<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Prompts;

use Aberdeener\LaravelMcpServer\Protocol\Entity;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\InvalidEntityParameterTypeException;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\PromptDescription;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\PromptName;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

abstract class Prompt extends Entity
{
    final public function name(): string
    {
        $reflectionClass = new ReflectionClass($this);
        $toolNameAttribute = $this->getAttributeValue($reflectionClass, PromptName::class, false);

        return $toolNameAttribute ?? Str::beforeLast(Str::snake($reflectionClass->getShortName()), '_prompt');
    }

    final public function description(): string
    {
        return $this->getAttributeValue(new ReflectionClass($this), PromptDescription::class);
    }

    final public function arguments(): array
    {
        $callMethod = new ReflectionMethod($this, 'call');
        $parameters = collect($callMethod->getParameters());
        $arguments = [];

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
            if ($type->getName() !== 'string') {
                throw new InvalidEntityParameterTypeException('Parameter type must be string', $parameterName, $typeName);
            }

            $arguments[] = [
                'name' => $parameter->getName(),
                'description' => $this->getAttributeValue($parameter, ParameterDescription::class, false),
                'required' => ! $parameter->isOptional(),
            ];
        }

        return $arguments;
    }

    final public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'description' => $this->description(),
            'arguments' => $this->arguments(),
        ];
    }
}
