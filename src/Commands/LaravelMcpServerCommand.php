<?php

namespace Aberdeener\LaravelMcpServer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LaravelMCPServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcp:stdio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Main STDIO command for MCP server';

    /**
     * Tracks if initialize response was sent.
     *
     * @var bool
     */
    private $initializeResponded = false;

    /**
     * Tracks when initialize response was sent for timeout.
     *
     * @var ?int
     */
    private $initializeResponseTime = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Disable output buffering
        ob_implicit_flush(true);

        // Open STDIN for reading
        $stdin = fopen('php://stdin', 'r');
        if ($stdin === false) {
            Log::error('Failed to open STDIN.');
            return 1;
        }

        // Initialize state
        $initialized = false;

        while (!feof($stdin)) {
            $line = fgets($stdin);
            if ($line === false) {
                usleep(100000); // 0.1 seconds to prevent CPU overload
                continue;
            }

            $line = trim($line);

            // Allow manual exit
            if ($line === 'exit') {
                Log::info('Received exit command. Stopping MCP server...');
                break;
            }

            // Skip empty lines
            if (empty($line)) {
                continue;
            }

            // Log raw input for debugging
            Log::debug('Raw input: ' . substr($line, 0, 200) . (strlen($line) > 200 ? '...' : ''));

            // Parse JSON-RPC message
            try {
                $message = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error('Invalid JSON: ' . $e->getMessage());
                continue;
            }

            // Validate JSON-RPC structure
            if (!is_array($message) || !isset($message['jsonrpc']) || $message['jsonrpc'] !== '2.0') {
                Log::error('Invalid JSON-RPC message: ' . json_encode($message));
                continue;
            }

            // Handle MCP messages
            if (isset($message['method'])) {
                $method = $message['method'];

                if ($method === 'initialize') {
                    // Respond to initialize request
                    $response = [
                        'jsonrpc' => '2.0',
                        'id' => $message['id'],
                        'result' => [
                            'protocolVersion' => '2024-11-05',
                            'capabilities' => [
                                "prompts" => [
                                  "listChanged" => false,
                                ],
                                "resources" => [
                                  "listChanged" => false,
                                ],
                                "tools" => [
                                  "listChanged" => false,
                                ]
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
                                    "name" => "get_weather",
                                    "description"=>  "Get current weather information for a location",
                                    "inputSchema"=>  [
                                      "type"=>  "object",
                                      "properties"=> [
                                        "location"=> [
                                          "type"=>  "string",
                                          "description"=>  "City name or zip code"
                                        ]
                                        ],
                                      "required"=> ["location"]
                                    ]
                                ]
                            ]
                        ]
                    ]);
                } elseif ($method === 'tools/call') {
                    if ($message['params']['name'] === 'get_weather' ) {
                        // Simulate a tool call
                        $location = $message['params']['arguments']['location'];
                        $response = [
                            'id' => $message['id'],
                            'jsonrpc' => '2.0',
                            'result' => [
                                "content" => [
                                    [
                                      "type" => "text",
                                      "text" =>"Current weather in {$location}:\nTemperature: 72°F\nConditions: Partly cloudy"
                                    ]
                                ],
                                "isError"=>false
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
                    Log::warning('Unhandled method: ' . $method);
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
                Log::warning('Unrecognized message format: ' . json_encode($message));
            }
        }

        // Clean up
        fclose($stdin);
        Log::info('MCP server stopped.');
        return 0;
    }

    private function sendJsonRpc(array $message): void
    {
        try {
            $json = json_encode($message, JSON_THROW_ON_ERROR);
            $output = $json . "\n";
            // Write to STDOUT without LOCK_EX
            $result = file_put_contents('php://stdout', $output);
            if ($result === false) {
                Log::error('Failed to write to STDOUT.');
                return;
            }
            fflush(STDOUT); // Ensure immediate flush to client
            Log::debug('Sent: ' . substr($json, 0, 200) . (strlen($json) > 200 ? '...' : ''));
        } catch (\JsonException $e) {
            Log::error('Failed to encode JSON response: ' . $e->getMessage());
        }
    }
}
