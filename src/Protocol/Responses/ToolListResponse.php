<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Tools\ToolRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ToolListResponse extends Response
{
    private ToolRegistry $toolRegistry;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $this->toolRegistry = app(ToolRegistry::class);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'tools' => $this->toolRegistry->allTools(),
            ],
        ];
    }
}
