<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Resources;

class FileResource extends Resource
{
    public function __construct(
        private string $uri,
        private string $name,
        private string $description,
    ) {
        parent::__construct(
            $uri,
            $name,
            $description,
            ResourceMimeType::Text,
        );
    }

    public function call(): string
    {
        return file_get_contents($this->uri);
    }
}
