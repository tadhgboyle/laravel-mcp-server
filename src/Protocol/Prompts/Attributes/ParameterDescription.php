<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ParameterDescription
{
    public function __construct(
        public string $value,
    ) {}
}
