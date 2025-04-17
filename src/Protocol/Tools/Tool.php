<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use ReflectionMethod;

abstract class Tool
{
    public function __construct(
        private string $name,
        private string $description,
    ) {
        if (!method_exists($this, 'call')) {
            throw new \RuntimeException('call method must be implemented');
        }
    }

    public final function name(): string
    {
        return $this->name;
    }

    public final function description(): string
    {
        return $this->description;
    }

    public final function inputSchema(): array
    {
        $callMethod = new ReflectionMethod($this, 'call');
        $parameters = $callMethod->getParameters();
        $inputSchema = [];

        foreach ($parameters as $parameter) {
            $inputSchema[$parameter->getName()] = [
                'type' => $parameter->getType()->getName(),
                'description' => array_filter($parameter->getAttributes(), fn($attr) => $attr->getName() === ParameterDescription::class)[0]->newInstance()->description,
            ];
        }

        return [
            'type' => 'object',
            'properties' => $inputSchema,
            'required' => array_map(fn($param) => $param->getName(), array_filter($parameters, fn($param) => !$param->isOptional())),
        ];
    }

    public final function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'inputSchema' => $this->inputSchema(),
        ];
    }
}
