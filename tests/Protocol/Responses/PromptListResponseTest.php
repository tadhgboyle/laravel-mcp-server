<?php

use Aberdeener\LaravelMcpServer\Protocol\Prompts\PromptRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Responses\PromptListResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyPrompt;

it('returns the list of prompts in their array format', function () {
    $promptRegistry = app(PromptRegistry::class);
    $promptRegistry->registerPrompt(new TestDummyPrompt);

    $response = new PromptListResponse(new Session, new Request);
    $attributes = $response->attributes();

    expect($attributes)->toBe([
        'result' => [
            'prompts' => [
                [
                    'name' => 'test_dummy',
                    'description' => 'An example prompt',
                    'arguments' => [
                        [
                            'name' => 'code1',
                            'description' => 'The first argument',
                            'required' => true,
                        ],
                        [
                            'name' => 'code2',
                            'description' => 'The second argument',
                            'required' => false,
                        ],
                    ],
                ],
            ],
        ],
    ]);
});
