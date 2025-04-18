<?php

use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\NamedDummyTool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyTool;
use Aberdeener\LaravelMcpServer\ToolRegistry;

it('can return a named tool', function () {
    $registry = new ToolRegistry;
    $registry->registerTool(new TestDummyTool);

    $retrievedTool = $registry->getTool('test_dummy');

    expect($retrievedTool)->toBeInstanceOf(Tool::class);
});

it('can return null for an unregistered tool', function () {
    $registry = new ToolRegistry;

    $retrievedTool = $registry->getTool('non_existent_tool');

    expect($retrievedTool)->toBeNull();
});

it('can return all registered tools', function () {
    $tool1 = new TestDummyTool;
    $tool2 = new NamedDummyTool;
    $registry = new ToolRegistry;
    $registry->registerTool($tool1);
    $registry->registerTool($tool2);

    $allTools = $registry->allTools();

    expect($allTools)->toHaveCount(2);
    expect($allTools)->toBe([
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
        [
            'name' => 'second_dummy',
            'description' => 'Another example tool',
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
    ]);
});
