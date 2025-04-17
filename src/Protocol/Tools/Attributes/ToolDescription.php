<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ToolDescription
{
    public function __construct(
        public string $description,
    ) {}
}
