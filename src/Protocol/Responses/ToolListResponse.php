<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\ToolRegistry;

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
