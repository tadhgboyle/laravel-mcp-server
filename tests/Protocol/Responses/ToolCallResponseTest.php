<?php

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\RequestException;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ToolCallResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\ErrorDummyTool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyTool;
use Aberdeener\LaravelMcpServer\ToolRegistry;

it('raises error when tool not found', function () {
    $request = new Request;
    $request->setMessage([
        'params' => [
            'name' => 'non_existent_tool',
            'arguments' => [1, 2],
        ],
    ]);
    new ToolCallResponse(new Session, $request);
})->throws(
    RequestException::class,
    'Tool not found: non_existent_tool',
);

it('returns error when error was raised during tool call', function () {
    $toolRegistry = app(ToolRegistry::class);
    $toolRegistry->registerTool(new ErrorDummyTool);

    $request = new Request;
    $request->setMessage([
        'params' => [
            'name' => 'error_dummy',
            'arguments' => [1, 2],
        ],
    ]);
    $response = new ToolCallResponse(new Session, $request);

    expect($response->attributes())->toEqual([
        'result' => [
            'content' => [
                [
                    'type' => 'text',
                    'text' => 'An error occurred',
                ],
            ],
            'isError' => true,
        ],
    ]);
});

it('returns tool response data for text result type', function () {
    $toolRegistry = app(ToolRegistry::class);
    $toolRegistry->registerTool(new TestDummyTool);

    $request = new Request;
    $request->setMessage([
        'params' => [
            'name' => 'test_dummy',
            'arguments' => [1, 2],
        ],
    ]);
    $response = new ToolCallResponse(new Session, $request);

    expect($response->attributes())->toEqual([
        'result' => [
            'content' => [
                [
                    'type' => 'text',
                    'text' => 3, // 1 + 2
                ],
            ],
            'isError' => false,
        ],
    ]);
});
