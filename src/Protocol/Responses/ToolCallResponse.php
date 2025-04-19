<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\RequestException;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ToolRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use InvalidArgumentException;
use Throwable;

class ToolCallResponse extends Response
{
    private Tool $tool;

    private array $arguments;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $toolName = $this->request->message()['params']['name'];
        $tool = app(ToolRegistry::class)->getTool($toolName);

        if (! $tool) {
            throw new RequestException(
                "Tool not found: {$toolName}",
                Error::EntityNotFound,
            );
        }

        $this->tool = $tool;
        $this->arguments = $this->request->message()['params']['arguments'];
    }

    public function attributes(): array
    {
        try {
            return [
                'result' => [
                    'content' => [
                        $this->toolContent($this->tool->call(...$this->arguments)),
                    ],
                    'isError' => false,
                ],
            ];
        } catch (Throwable $exception) {
            return [
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $exception->getMessage(),
                        ],
                    ],
                    'isError' => true,
                ],
            ];
        }
    }

    private function toolContent(string $toolCallResponse): array
    {
        switch ($this->tool->resultType()) {
            case ResultType::Text:
                return [
                    'type' => 'text',
                    'text' => $toolCallResponse,
                ];
            default:
                throw new InvalidArgumentException('Invalid result type');
        }
    }
}
