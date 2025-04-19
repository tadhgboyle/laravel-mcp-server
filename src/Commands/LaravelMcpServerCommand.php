<?php

namespace Aberdeener\LaravelMcpServer\Commands;

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\RequestException;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ErrorResponse;
use Aberdeener\LaravelMcpServer\Protocol\Responses\Response;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LaravelMcpServerCommand extends Command
{
    protected $signature = 'mcp:stdio';

    protected $description = 'Main STDIO command for MCP server';

    public function handle()
    {
        ob_implicit_flush(true);

        $stdin = fopen('php://stdin', 'r');

        $session = new Session;

        while (! feof($stdin)) {
            $line = fgets($stdin);
            if ($line === false) {
                usleep(100000);

                continue;
            }

            $line = trim($line);

            Log::debug('Input: '.$line);

            $message = json_decode($line, true);

            $request = new Request;

            $request->setMessage($message);

            $method = $message['method'];

            if (str_starts_with($message['method'], 'notifications/')) {
                if ($method === 'notifications/initialized') {
                    Log::info('Received initialized notification.');

                    continue;
                } elseif ($method === 'notifications/cancelled') {
                    Log::info('Received cancelled notification.');
                    break;
                }
            }

            $request->setId($message['id']);

            if (! array_key_exists($method, Response::REQUEST_HANDLERS)) {
                $this->sendResponse(new ErrorResponse(
                    $session,
                    $request,
                    'Method not found',
                    Error::MethodNotFound,
                ));

                continue;
            }

            $response = Response::REQUEST_HANDLERS[$method];

            try {
                $this->sendResponse(new $response(
                    $session,
                    $request,
                ));
            } catch (RequestException $exception) {
                $this->sendResponse(new ErrorResponse(
                    $session,
                    $request,
                    $exception->getMessage(),
                    Error::from($exception->getCode()),
                ));

                continue;
            }
        }

        fclose($stdin);
        Log::info('MCP server stopped.');

        return self::SUCCESS;
    }

    private function sendResponse(Response $response): void
    {
        $json = json_encode($response->toArray(), JSON_THROW_ON_ERROR);
        file_put_contents('php://stdout', $json."\n");
        fflush(STDOUT);
        Log::debug('Sent: '.$json);
    }
}
