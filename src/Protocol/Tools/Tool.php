<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools;

use Aberdeener\LaravelMcpServer\Protocol\Entity;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\InvalidEntityParameterTypeException;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolName;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolResultType;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

abstract class Tool extends Entity
{
    private const EQUIVALENT_TYPES = [
        'int' => 'integer',
        'bool' => 'boolean',
        'float' => 'number',
        'string' => 'string',
        'array' => 'array',
    ];

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
                throw new InvalidEntityParameterTypeException('Variadic parameters are not supported', $parameterName);
            }

            if (! $parameter->hasType()) {
                throw new InvalidEntityParameterTypeException('Parameter type is not defined', $parameterName);
            }

            $type = $parameter->getType();
            $typeName = $type->getName();
            if (! $type->isBuiltin()) {
                throw new InvalidEntityParameterTypeException('Parameter type is not a built-in type', $parameterName, $typeName);
            }

            $equivalentType = self::EQUIVALENT_TYPES[$type->getName()] ?? null;
            if ($equivalentType === null) {
                throw new InvalidEntityParameterTypeException('Parameter type is not supported', $parameterName, $typeName);
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
}
