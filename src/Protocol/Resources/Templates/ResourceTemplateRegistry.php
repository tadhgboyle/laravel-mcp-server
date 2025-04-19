<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Resources\Templates;

use Illuminate\Support\Collection;

class ResourceTemplateRegistry
{
    private Collection $resourceTemplates;

    public function __construct()
    {
        $this->resourceTemplates = collect();
    }

    public function registerResourceTemplate(ResourceTemplate $resourceTemplate): void
    {
        $this->resourceTemplates[] = $resourceTemplate;
    }

    public function allResourceTemplates(): array
    {
        return $this->resourceTemplates->values()->map->toArray()->all();
    }
}
