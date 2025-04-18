<?php

namespace Aberdeener\LaravelMcpServer;

use Aberdeener\LaravelMcpServer\Protocol\Prompts\Prompt;
use Illuminate\Support\Collection;

class PromptRegistry
{
    private Collection $prompts;

    public function __construct()
    {
        $this->prompts = collect();
    }

    public function registerPrompt(Prompt $prompt): void
    {
        $this->prompts[$prompt->name()] = $prompt;
    }

    public function getPrompt(string $name): ?Prompt
    {
        return $this->prompts[$name] ?? null;
    }

    public function allPrompts(): array
    {
        return $this->prompts->values()->map->toArray()->all();
    }
}
