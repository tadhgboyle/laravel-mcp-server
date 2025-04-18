<?php

use Aberdeener\LaravelMcpServer\Protocol\Responses\ToolCallResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\ErrorDummyTool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyTool;

it('returns error when error was raised during tool call', function () {
    $response = new ToolCallResponse(new Session, new Request, new ErrorDummyTool, [1, 2]);

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
    $response = new ToolCallResponse(new Session, new Request, new TestDummyTool, [1, 2]);

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
