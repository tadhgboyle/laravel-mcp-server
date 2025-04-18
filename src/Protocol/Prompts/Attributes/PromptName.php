<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class PromptName
{
    public function __construct(
        public string $value,
    ) {}
}
