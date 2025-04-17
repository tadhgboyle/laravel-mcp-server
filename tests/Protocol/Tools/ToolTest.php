<?php

use Aberdeener\LaravelMcpServer\Tests\Fixtures\NamedDummyTool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyTool;

it('can get the name of the tool without ToolName attribute', function () {
    $tool = new TestDummyTool;
    expect($tool->name())->toBe('test_dummy');
});

it('can get the name of the tool with ToolName attribute', function () {
    $tool = new NamedDummyTool;
    expect($tool->name())->toBe('second_dummy');
});

it('can get the result type of the tool', function () {
    $tool = new TestDummyTool;
    expect($tool->resultType())->toBe(\Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType::Text);
});

it('can get the input schema of the tool', function () {
    $tool = new TestDummyTool;
    $schema = $tool->inputSchema();

    expect($schema)->toBe([
        'type' => 'object',
        'properties' => [
            'arg1' => [
                'type' => 'int',
                'description' => 'The first argument',
            ],
            'arg2' => [
                'type' => 'int',
                'description' => 'The second argument',
            ],
        ],
        'required' => ['arg1'],
    ]);
});

it('can get array serialized tool', function () {
    $tool = new TestDummyTool;
    $serialized = $tool->toArray();

    expect($serialized)->toBe([
        'name' => 'test_dummy',
        'description' => 'An example tool',
        'inputSchema' => [
            'type' => 'object',
            'properties' => [
                'arg1' => [
                    'type' => 'int',
                    'description' => 'The first argument',
                ],
                'arg2' => [
                    'type' => 'int',
                    'description' => 'The second argument',
                ],
            ],
            'required' => ['arg1'],
        ],
    ]);
});

it('can call the tool', function () {
    $tool = new TestDummyTool;
    $result = $tool->call(1, 2);

    expect($result)->toBe(3);
});
