<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools;

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\InvalidToolParameterTypeException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\ToolMustProvideCallMethodException;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolName;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolResultType;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * @method mixed call(...$args)
 */
abstract class Tool
{
    private const EQUIVALENT_TYPES = [
        'int' => 'integer',
        'bool' => 'boolean',
        'float' => 'number',
        'string' => 'string',
        'array' => 'array',
        'object' => 'object',
    ];

    public function __construct()
    {
        if (! method_exists($this, 'call')) {
            throw new ToolMustProvideCallMethodException;
        }
    }

    final public function name(): string
    {
        $reflectionClass = new ReflectionClass($this);
        $toolNameAttribute = $this->getAttributeValue($reflectionClass, ToolName::class, false);

        return $toolNameAttribute ?? Str::beforeLast(Str::snake($reflectionClass->getShortName()), '_tool');
    }

    final public function resultType(): ResultType
    {
        $resultType = $this->getAttributeValue(new ReflectionClass($this), ToolResultType::class);

        return $resultType;
    }

    final public function inputSchema(): array
    {
        $callMethod = new ReflectionMethod($this, 'call');
        $parameters = $callMethod->getParameters();
        $inputSchema = [];

        foreach ($parameters as $parameter) {
            if ($parameter->isVariadic()) {
                throw new InvalidToolParameterTypeException;
            }

            if (! $parameter->hasType()) {
                throw new InvalidToolParameterTypeException;
            }

            $type = $parameter->getType();
            if ($type === null) {
                throw new InvalidToolParameterTypeException;
            }
            if (! $type->isBuiltin()) {
                throw new InvalidToolParameterTypeException;
            }

            $equivalentType = self::EQUIVALENT_TYPES[$type->getName()] ?? null;
            if ($equivalentType === null) {
                throw new InvalidToolParameterTypeException;
            }

            $inputSchema[$parameter->getName()] = [
                'type' => $equivalentType,
                'description' => $this->getAttributeValue($parameter, ParameterDescription::class),
            ];
        }

        return [
            'type' => 'object',
            'properties' => $inputSchema,
            'required' => array_map(fn ($param) => $param->getName(), array_filter($parameters, fn ($param) => ! $param->isOptional())),
        ];
    }

    final public function toArray(): array
    {
        $description = $this->getAttributeValue(new ReflectionClass($this), ToolDescription::class);

        return [
            'name' => $this->name(),
            'description' => $description,
            'inputSchema' => $this->inputSchema(),
        ];
    }

    private function getAttributeValue(ReflectionClass|ReflectionParameter $reflector, $attribute, bool $raise = true)
    {
        $attributes = collect($reflector->getAttributes())->filter(fn (ReflectionAttribute $attr) => $attr->getName() === $attribute);
        if ($attributes->isEmpty()) {
            if (! $raise) {
                return null;
            } else {
                throw new \RuntimeException('Attribute not found');
            }
        }

        if ($attributes->count() > 1) {
            throw new \RuntimeException('Multiple attributes found');
        }

        return $attributes->first()->newInstance()->value;
    }
}
