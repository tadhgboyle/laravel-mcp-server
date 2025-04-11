<?php

namespace Aberdeener\LaravelMcpServer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LaravelMCPServerCommand extends Command
{
    protected $signature = 'mcp:stdio';

    protected $description = 'Main STDIO command for MCP server';

    public function handle()
    {
        ob_implicit_flush(true);

        $stdin = fopen('php://stdin', 'r');
        if ($stdin === false) {
            Log::error('Failed to open STDIN.');

            return 1;
        }

        while (! feof($stdin)) {
            $line = fgets($stdin);
            if ($line === false) {
                usleep(100000);

                continue;
            }

            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            Log::debug('Raw input: '.substr($line, 0, 200).(strlen($line) > 200 ? '...' : ''));

            try {
                $message = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error('Invalid JSON: '.$e->getMessage());

                continue;
            }

            if (! is_array($message) || ! isset($message['jsonrpc']) || $message['jsonrpc'] !== '2.0') {
                Log::error('Invalid JSON-RPC message: '.json_encode($message));

                continue;
            }

            if (isset($message['method'])) {
                $method = $message['method'];

                if ($method === 'initialize') {
                    $response = [
                        'jsonrpc' => '2.0',
                        'id' => $message['id'],
                        'result' => [
                            'protocolVersion' => '2024-11-05',
                            'capabilities' => [
                                'prompts' => [
                                    'listChanged' => false,
                                ],
                                'resources' => [
                                    'listChanged' => false,
                                ],
                                'tools' => [
                                    'listChanged' => false,
                                ],
                            ],
                            'serverInfo' => [
                                'name' => 'Laravel MCP Server',
                                'version' => '1.0.0',
                            ],
                        ],
                    ];

                    $this->sendJsonRpc($response);
                } elseif ($method === 'tools/list') {
                    $this->sendJsonRpc([
                        'id' => $message['id'],
                        'jsonrpc' => '2.0',
                        'result' => [
                            'tools' => [
                                [
                                    'name' => 'get_weather',
                                    'description' => 'Get current weather information for a location',
                                    'inputSchema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'location' => [
                                                'type' => 'string',
                                                'description' => 'City name or zip code',
                                            ],
                                        ],
                                        'required' => ['location'],
                                    ],
                                ],
                            ],
                        ],
                    ]);
                } elseif ($method === 'tools/call') {
                    if ($message['params']['name'] === 'get_weather') {
                        $location = $message['params']['arguments']['location'];
                        $response = [
                            'id' => $message['id'],
                            'jsonrpc' => '2.0',
                            'result' => [
                                'content' => [
                                    [
                                        'type' => 'text',
                                        'text' => "Current weather in {$location}:\nTemperature: 72°F\nConditions: Partly cloudy",
                                    ],
                                ],
                                'isError' => false,
                            ],
                        ];
                        $this->sendJsonRpc($response);
                    }
                } elseif ($method === 'notifications/initialized') {
                    Log::info('Received initialized notification.');
                } elseif ($method === 'notifications/cancelled') {
                    Log::info('Received cancelled notification.');
                    break;
                } else {
                    Log::warning('Unhandled method: '.$method);
                    if (isset($message['id'])) {
                        $response = [
                            'jsonrpc' => '2.0',
                            'id' => $message['id'],
                            'error' => [
                                'code' => -32601,
                                'message' => 'Method not found',
                            ],
                        ];
                        $this->sendJsonRpc($response);
                    }
                }
            } else {
                Log::warning('Unrecognized message format: '.json_encode($message));
            }
        }

        fclose($stdin);
        Log::info('MCP server stopped.');

        return 0;
    }

    private function sendJsonRpc(array $message): void
    {
        $json = json_encode($message, JSON_THROW_ON_ERROR);
        file_put_contents('php://stdout', $json."\n");
        fflush(STDOUT);
        Log::debug('Sent: '.$json);
    }
}
