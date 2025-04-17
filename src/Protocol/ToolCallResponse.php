<?php

namespace Aberdeener\LaravelMcpServer\Protocol;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ToolCallResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private $toolCallResponse,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $this->toolCallResponse,
                    ],
                ],
                'isError' => false,
            ],
        ];
    }
}
