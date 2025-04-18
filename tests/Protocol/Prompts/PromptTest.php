<?php

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\EntityAttributeMissingException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\EntityMustProvideCallMethodException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\MultipleEntityAttributesDefinedException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\InvalidEntityParameterTypeException;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\PromptDescription;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Prompt;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\NamedDummyPrompt;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyPrompt;

it('raises an exception if the call method is not defined', function () {
    new class extends Prompt {};
})->throws(EntityMustProvideCallMethodException::class);

it('can get the name of the prompt without PromptName attribute', function () {
    $prompt = new TestDummyPrompt;
    expect($prompt->name())->toBe('test_dummy');
});

it('can get the name of the prompt with PromptName attribute', function () {
    $prompt = new NamedDummyPrompt;
    expect($prompt->name())->toBe('second_dummy');
});

it('can get the description of the prompt', function () {
    $prompt = new TestDummyPrompt;
    expect($prompt->description())->toBe('An example prompt');
});

it('raises an exception if no PromptDescription attribute is defined', function () {
    new class extends Prompt {
        public function call()
        {
        }
    }->description();
})->throws(EntityAttributeMissingException::class, 'The PromptDescription attribute is missing.');

it('raises an exception if multiple PromptDescription attributes are defined', function () {
    new PromptWithMultipleAttributes()->description();
})->throws(MultipleEntityAttributesDefinedException::class, 'Multiple PromptDescription attributes are defined.');

it('can get the arguments of the prompt', function () {
    $prompt = new TestDummyPrompt;
    $schema = $prompt->arguments();

    expect($schema)->toBe([
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
    ]);
});

it('raises an exception if a parameter is variadic', function () {
    new class extends Prompt {
        public function call(...$args)
        {
        }
    }->arguments();
})->throws(InvalidEntityParameterTypeException::class, "Variadic parameters are not supported (parameter: 'args').");

it('raises an exception if a parameter type is not defined', function () {
    new class extends Prompt {
        public function call($arg1)
        {
        }
    }->arguments();
})->throws(InvalidEntityParameterTypeException::class, "Parameter type is not defined (parameter: 'arg1').");

it('raises an exception if a parameter type is not a string', function () {
    new class extends Prompt {
        public function call(int $arg1)
        {
        }
    }->arguments();
})->throws(InvalidEntityParameterTypeException::class, "Parameter type must be string (parameter: 'arg1') (type: 'int').");

it('can get array serialized prompt', function () {
    $prompt = new TestDummyPrompt;
    $serialized = $prompt->toArray();

    expect($serialized)->toBe([
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
    ]);
});

it('can call the prompt', function () {
    $prompt = new TestDummyPrompt;
    $result = $prompt->call('echo 1;', 'echo 2;');

    expect($result)->toBe(
        "Please evaluate the following PHP code for style issues: echo 1;, echo 2;"
    );
});

#[PromptDescription('An example prompt')]
#[PromptDescription('Another example prompt')]
class PromptWithMultipleAttributes extends Prompt
{
    public function call()
    {
    }
}
