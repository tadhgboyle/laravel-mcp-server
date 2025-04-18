<?php

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\EntityAttributeMissingException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\EntityMustProvideCallMethodException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\InvalidEntityParameterTypeException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\MultipleEntityAttributesDefinedException;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\ComplexTestDummyTool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\NamedDummyTool;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\TestDummyTool;
use Illuminate\Support\Facades\DB;

it('raises an exception if the call method is not defined', function () {
    new class extends Tool {};
})->throws(EntityMustProvideCallMethodException::class);

it('can get the name of the tool without ToolName attribute', function () {
    $tool = new TestDummyTool;
    expect($tool->name())->toBe('test_dummy');
});

it('can get the name of the tool with ToolName attribute', function () {
    $tool = new NamedDummyTool;
    expect($tool->name())->toBe('second_dummy');
});

it('can get the description of the tool', function () {
    $tool = new TestDummyTool;
    expect($tool->description())->toBe('An example tool');
});

it('raises an exception if no ToolDescription attribute is defined', function () {
    new class extends Tool
    {
        public function call() {}
    }->description();
})->throws(EntityAttributeMissingException::class, 'The ToolDescription attribute is missing.');

it('raises an exception if multiple ToolDescription attributes are defined', function () {
    (new ToolWithMultipleAttributes)->description();
})->throws(MultipleEntityAttributesDefinedException::class, 'Multiple ToolDescription attributes are defined.');

it('can get the result type of the tool', function () {
    $tool = new TestDummyTool;
    expect($tool->resultType())->toBe(ResultType::Text);
});

it('raises an exception if multiple ToolResultType attributes are defined', function () {
    (new ToolWithMultipleAttributes)->resultType();
})->throws(MultipleEntityAttributesDefinedException::class, 'Multiple ToolResultType attributes are defined.');

it('raises an exception if the ToolResultType attribute is missing', function () {
    new class extends Tool
    {
        public function call() {}
    }->resultType();
})->throws(EntityAttributeMissingException::class, 'The ToolResultType attribute is missing.');

it('can get the input schema of the tool', function () {
    $tool = new ComplexTestDummyTool;
    $schema = $tool->inputSchema();

    expect($schema)->toBe([
        'type' => 'object',
        'properties' => [
            'arg1' => [
                'type' => 'integer',
                'description' => 'The first argument',
            ],
            'arg2' => [
                'type' => 'string',
                'description' => 'The second argument',
            ],
            'arg3' => [
                'type' => 'number',
                'description' => 'The third argument',
            ],
            'arg4' => [
                'type' => 'array',
                'description' => 'The fourth argument',
            ],
            'arg5' => [
                'type' => 'boolean',
                'description' => 'The fifth argument',
            ],
        ],
        'required' => ['arg1'],
    ]);
});

it('raises an exception if a parameter is variadic', function () {
    new class extends Tool
    {
        public function call(...$args) {}
    }->inputSchema();
})->throws(InvalidEntityParameterTypeException::class, "Variadic parameters are not supported (parameter: 'args').");

it('raises an exception if a parameter type is not defined', function () {
    new class extends Tool
    {
        public function call($arg1) {}
    }->inputSchema();
})->throws(InvalidEntityParameterTypeException::class, "Parameter type is not defined (parameter: 'arg1').");

it('raises an exception if a parameter type is not builtin', function () {
    new class extends Tool
    {
        public function call(DB $arg1) {}
    }->inputSchema();
})->throws(InvalidEntityParameterTypeException::class, "Parameter type is not a built-in type (parameter: 'arg1') (type: 'Illuminate\Support\Facades\DB').");

it('raises an exception if a parameter type is not supported', function () {
    new class extends Tool
    {
        public function call(object $arg1) {}
    }->inputSchema();
})->throws(InvalidEntityParameterTypeException::class, "Parameter type is not supported (parameter: 'arg1') (type: 'object').");

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
    ]);
});

it('can call the tool', function () {
    $tool = new TestDummyTool;
    $result = $tool->call(1, 2);

    expect($result)->toBe(3);
});

#[ToolResultType(ResultType::Text)]
#[ToolResultType(ResultType::Image)]
#[ToolDescription('An example tool')]
#[ToolDescription('Another example tool')]
class ToolWithMultipleAttributes extends Tool
{
    public function call() {}
}
