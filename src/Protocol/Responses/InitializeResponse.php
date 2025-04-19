<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class InitializeResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $this->session->setInitialized();
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'protocolVersion' => '2024-11-05',
                'capabilities' => [
                    'prompts' => [
                        'listChanged' => false,
                    ],
                    'resources' => [
                        'subscribe' => false,
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
    }
}
