<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * @method mixed call(...$args)
 */
abstract class Tool
{
    public function __construct()
    {
        if (! method_exists($this, 'call')) {
            throw new \RuntimeException('call method must be implemented');
        }
    }

    final public function name(): string
    {
        $reflectionClass = new ReflectionClass($this);
        $toolNameAttribute = collect($reflectionClass->getAttributes())->filter(fn ($attr) => $attr->getName() === Attributes\ToolName::class);
        if ($toolNameAttribute->isEmpty()) {
            return Str::beforeLast(Str::snake($reflectionClass->getShortName()), '_tool');
        } else {
            return $toolNameAttribute->first()->newInstance()->name;
        }
    }

    final public function resultType(): ResultType
    {
        $reflectionClass = new ReflectionClass($this);
        $attributes = $reflectionClass->getAttributes();
        $resultType = array_filter($attributes, fn ($attr) => $attr->getName() === Attributes\ToolResultType::class)[0]->newInstance()->resultType;

        return $resultType;
    }

    final public function inputSchema(): array
    {
        $callMethod = new ReflectionMethod($this, 'call');
        $parameters = $callMethod->getParameters();
        $inputSchema = [];

        foreach ($parameters as $parameter) {
            $inputSchema[$parameter->getName()] = [
                'type' => $parameter->getType()->getName(),
                'description' => array_filter($parameter->getAttributes(), fn ($attr) => $attr->getName() === ParameterDescription::class)[0]->newInstance()->description,
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
        $reflectionClass = new ReflectionClass($this);
        $description = collect($reflectionClass->getAttributes())->filter(fn ($attr) => $attr->getName() === Attributes\ToolDescription::class)->first()->newInstance()->description;

        return [
            'name' => $this->name(),
            'description' => $description,
            'inputSchema' => $this->inputSchema(),
        ];
    }
}
