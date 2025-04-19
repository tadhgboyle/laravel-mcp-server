<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Resources;

use Aberdeener\LaravelMcpServer\Protocol\Entity;

abstract class Resource extends Entity
{
    public function __construct(
        private string $uri,
        private string $name,
        private string $description,
        private ResourceMimeType $mimeType,
    ) {
        parent::__construct();
    }

    final public function uri(): string
    {
        return $this->uri;
    }

    final public function name(): string
    {
        return $this->name;
    }

    final public function description(): string
    {
        return $this->description;
    }

    final public function mimeType(): ResourceMimeType
    {
        return $this->mimeType;
    }

    final public function toArray(): array
    {
        return [
            'uri' => $this->uri(),
            'name' => $this->name(),
            'description' => $this->description(),
            'mimeType' => $this->mimeType()->value,
        ];
    }
}
