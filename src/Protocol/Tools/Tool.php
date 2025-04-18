<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools;

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\InvalidToolParameterTypeException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\MultipleToolAttributesDefinedException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\ToolAttributeMissingException;
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

    final public function description(): string
    {
        return $this->getAttributeValue(new ReflectionClass($this), ToolDescription::class);
    }

    final public function resultType(): ResultType
    {
        return $this->getAttributeValue(new ReflectionClass($this), ToolResultType::class);
    }

    final public function inputSchema(): array
    {
        $callMethod = new ReflectionMethod($this, 'call');
        $parameters = collect($callMethod->getParameters());
        $inputSchema = [];

        foreach ($parameters as $parameter) {
            $parameterName = $parameter->getName();

            if ($parameter->isVariadic()) {
                throw new InvalidToolParameterTypeException('Variadic parameters are not supported', $parameterName);
            }

            if (! $parameter->hasType()) {
                throw new InvalidToolParameterTypeException('Parameter type is not defined', $parameterName);
            }

            $type = $parameter->getType();
            $typeName = $type->getName();
            if (! $type->isBuiltin()) {
                throw new InvalidToolParameterTypeException('Parameter type is not a built-in type', $parameterName, $typeName);
            }

            $equivalentType = self::EQUIVALENT_TYPES[$type->getName()] ?? null;
            if ($equivalentType === null) {
                throw new InvalidToolParameterTypeException('Parameter type is not supported', $parameterName, $typeName);
            }

            $inputSchema[$parameter->getName()] = [
                'type' => $equivalentType,
                'description' => $this->getAttributeValue($parameter, ParameterDescription::class),
            ];
        }

        return [
            'type' => 'object',
            'properties' => $inputSchema,
            'required' => $parameters->reject(function (ReflectionParameter $parameter) {
                return $parameter->isOptional();
            })->map(function (ReflectionParameter $parameter) {
                return $parameter->getName();
            })->values()->all(),
        ];
    }

    final public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'description' => $this->description(),
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
                throw new ToolAttributeMissingException($attribute);
            }
        }

        if ($attributes->count() > 1) {
            throw new MultipleToolAttributesDefinedException($attribute);
        }

        return $attributes->first()->newInstance()->value;
    }
}
