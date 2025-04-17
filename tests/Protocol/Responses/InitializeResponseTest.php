<?php

use Aberdeener\LaravelMcpServer\Protocol\Responses\InitializeResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

it('returns initialization data', function () {
    $response = new InitializeResponse(
        new Session,
        new Request
    );

    expect($response->attributes())->toBe([
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
    ]);
});
