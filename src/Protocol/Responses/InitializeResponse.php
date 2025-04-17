<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

class InitializeResponse extends Response
{
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
