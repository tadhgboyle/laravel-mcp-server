<?php

namespace Aberdeener\LaravelMcpServer;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;
use Illuminate\Support\Collection;

class ToolRegistry
{
    private Collection $tools;

    public function __construct()
    {
        $this->tools = collect();
    }

    public function registerTool(Tool $tool): void
    {
        $this->tools[$tool->name()] = $tool;
    }

    public function getTool(string $name): ?Tool
    {
        return $this->tools[$name] ?? null;
    }

    public function allTools(): array
    {
        return $this->tools->values()->map->toArray()->all()[0];
    }
}
