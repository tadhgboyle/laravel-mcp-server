<?php

use Aberdeener\LaravelMcpServer\Protocol\Responses\ToolListResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyTool;
use Aberdeener\LaravelMcpServer\ToolRegistry;

it('returns the list of tools in their array format', function () {
    $toolRegistry = app(ToolRegistry::class);
    $toolRegistry->registerTool(new TestDummyTool);

    $response = new ToolListResponse(new Session, new Request, $toolRegistry);
    $attributes = $response->attributes();

    expect($attributes)->toBe([
        'result' => [
            'tools' => [
                [
                    'name' => 'test_dummy',
                    'description' => 'An example tool',
                    'inputSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'arg1' => [
                                'type' => 'integer',
                                'description' => 'The first argument',
                            ],
                            'arg2' => [
                                'type' => 'integer',
                                'description' => 'The second argument',
                            ],
                        ],
                        'required' => ['arg1'],
                    ],
                ],
            ],
        ],
    ]);
});
