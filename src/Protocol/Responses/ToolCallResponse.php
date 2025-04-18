<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use InvalidArgumentException;
use Throwable;

class ToolCallResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private Tool $tool,
        private array $arguments,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        $toolCallResponse = $this->toolCallResponse();
        if (isset($toolCallResponse['error'])) {
            return [
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $toolCallResponse['error'],
                        ],
                    ],
                    'isError' => true,
                ],
            ];
        }

        return [
            'result' => [
                'content' => [
                    $this->toolContent($toolCallResponse['response']),
                ],
                'isError' => false,
            ],
        ];
    }

    private function toolCallResponse(): array
    {
        try {
            return ['response' => $this->tool->call(...$this->arguments)];
        } catch (Throwable $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    private function toolContent($toolCallResponse): array
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
