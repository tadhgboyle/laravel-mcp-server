<?php

use Aberdeener\LaravelMcpServer\Protocol\Prompts\Prompt;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\PromptRegistry;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\NamedDummyPrompt;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyPrompt;

it('can return a named prompt', function () {
    $registry = new PromptRegistry;
    $registry->registerPrompt(new TestDummyPrompt);

    $retrievedPrompt = $registry->getPrompt('test_dummy');

    expect($retrievedPrompt)->toBeInstanceOf(Prompt::class);
});

it('can return null for an unregistered tool', function () {
    $registry = new PromptRegistry;

    $retrievedPrompt = $registry->getPrompt('non_existent_prompt');

    expect($retrievedPrompt)->toBeNull();
});

it('can return all registered prompts', function () {
    $registry = new PromptRegistry;
    $registry->registerPrompt(new TestDummyPrompt);
    $registry->registerPrompt(new NamedDummyPrompt);

    $allPrompts = $registry->allPrompts();

    expect($allPrompts)->toHaveCount(2);
    expect($allPrompts)->toBe([
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
        [
            'name' => 'second_dummy',
            'description' => 'Another example prompt',
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
    ]);
});
