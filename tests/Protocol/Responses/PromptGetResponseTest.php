<?php

use Aberdeener\LaravelMcpServer\Protocol\Prompts\PromptRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Responses\PromptGetResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyPrompt;

it('returns prompt response data', function () {
    $promptRegistry = app(PromptRegistry::class);
    $promptRegistry->registerPrompt(new TestDummyPrompt);

    $request = new Request;
    $request->setMessage([
        'params' => [
            'name' => 'test_dummy',
            'arguments' => [
                'echo "Hello world!";',
            ],
        ],
    ]);

    $response = new PromptGetResponse(new Session, $request);

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
