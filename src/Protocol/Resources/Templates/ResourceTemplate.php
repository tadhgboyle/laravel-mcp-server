<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Resources\Templates;

use Aberdeener\LaravelMcpServer\Protocol\Entity;

class ResourceTemplate extends Entity
{
    public function __construct(
        private string $uriTemplate,
        private string $name,
        private string $description,
        private ResourceTemplateMimeType $mimeType,
    ) {}

    final public function uriTemplate(): string
    {
        return $this->uriTemplate;
    }

    final public function name(): string
    {
        return $this->name;
    }

    final public function description(): string
    {
        return $this->description;
    }

    final public function mimeType(): ResourceTemplateMimeType
    {
        return $this->mimeType;
    }

    final public function toArray(): array
    {
        return [
            'uriTemplate' => $this->uriTemplate(),
            'name' => $this->name(),
            'description' => $this->description(),
            'mimeType' => $this->mimeType()->value,
        ];
    }
}
