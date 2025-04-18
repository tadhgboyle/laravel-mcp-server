<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ToolName
{
    public function __construct(
        public string $value,
    ) {}
}
