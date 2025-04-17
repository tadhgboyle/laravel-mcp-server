<?php

namespace Aberdeener\LaravelMcpServer\Protocol;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\ToolRegistry;

class ToolListResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private ToolRegistry $toolRegistry,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'tools' => [
                    $this->toolRegistry->allTools(),
                ],
            ],
        ];
    }
}
