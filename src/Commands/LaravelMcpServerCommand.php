<?php

namespace Aberdeener\LaravelMcpServer\Commands;

use Aberdeener\LaravelMcpServer\Protocol\Responses\ErrorResponse;
use Aberdeener\LaravelMcpServer\Protocol\Responses\InitializeResponse;
use Aberdeener\LaravelMcpServer\Protocol\Responses\PingResponse;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ToolCallResponse;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ToolListResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\ToolRegistry;
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

            return self::FAILURE;
        }

        $session = new Session;
        $request = new Request;

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

            Log::debug('Raw input: '.$line);

            $message = json_decode($line, true);

            if (isset($message['method'])) {
                if (! str_starts_with($message['method'], 'notifications/')) {
                    $request->setId($message['id']);
                }

                $method = $message['method'];

                if ($method === 'initialize') {
                    $response = new InitializeResponse(
                        $session,
                        $request
                    )->toArray();
                    $session->setInitialized();
                    $this->sendJsonRpc($response);
                } elseif ($method === 'tools/list') {
                    $response = new ToolListResponse(
                        $session,
                        $request,
                        app(ToolRegistry::class),
                    )->toArray();
                    $this->sendJsonRpc($response);
                } elseif ($method === 'tools/call') {
                    $toolRegistry = app(ToolRegistry::class);
                    $tool = $toolRegistry->getTool($message['params']['name']);
                    if ($tool === null) {
                        Log::warning('Tool not found: '.$message['params']['name']);
                        $response = new ErrorResponse(
                            $session,
                            $request,
                            "Unknown tool: {$message['params']['name']}",
                            -32602,
                        )->toArray();
                        $this->sendJsonRpc($response);
                    }

                    $this->sendJsonRpc(new ToolCallResponse(
                        $session,
                        $request,
                        $tool,
                        $message['params']['arguments'],
                    )->toArray());
                } elseif ($method === 'notifications/initialized') {
                    Log::info('Received initialized notification.');
                } elseif ($method === 'notifications/cancelled') {
                    Log::info('Received cancelled notification.');
                    break;
                } elseif ($method === 'ping') {
                    Log::info('Received ping notification.');
                    $response = new PingResponse(
                        $session,
                        $request,
                    )->toArray();
                    $this->sendJsonRpc($response);
                } else {
                    Log::warning('Unhandled method: '.$method);
                    if (isset($message['id'])) {
                        $response = new ErrorResponse(
                            $session,
                            $request,
                            'Method not found',
                            -32601,
                        )->toArray();
                        $this->sendJsonRpc($response);
                    }
                }
            } else {
                Log::warning('Unrecognized message format: '.json_encode($message));
            }
        }

        fclose($stdin);
        Log::info('MCP server stopped.');

        return self::SUCCESS;
    }

    private function sendJsonRpc(array $message): void
    {
        $json = json_encode($message, JSON_THROW_ON_ERROR);
        file_put_contents('php://stdout', $json."\n");
        fflush(STDOUT);
        Log::debug('Sent: '.$json);
    }
}
