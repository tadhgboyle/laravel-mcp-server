<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Resources;

use Illuminate\Support\Collection;

class ResourceRegistry
{
    private Collection $resources;

    public function __construct()
    {
        $this->resources = collect();
    }

    public function registerResource(Resource $resource): void
    {
        $this->resources[$resource->uri()] = $resource;
    }

    public function getResource(string $uri): ?Resource
    {
        return $this->resources[$uri] ?? null;
    }

    public function allResources(): array
    {
        return $this->resources->values()->map->toArray()->all();
    }
}
