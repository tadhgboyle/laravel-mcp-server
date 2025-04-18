<?php

use Aberdeener\LaravelMcpServer\Protocol\Responses\PromptGetResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyPrompt;

it('returns prompt response data', function () {
    $response = new PromptGetResponse(new Session, new Request, new TestDummyPrompt, ['echo "Hello world!";']);

    expect($response->attributes())->toEqual([
        'result' => [
            'description' => 'An example prompt',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        'type' => 'text',
                        'text' => 'Please evaluate the following PHP code for style issues: echo "Hello world!";',
                    ],
                ],
            ],
        ],
    ]);
});
