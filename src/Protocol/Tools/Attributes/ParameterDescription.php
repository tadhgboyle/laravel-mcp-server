<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ParameterDescription
{
    public function __construct(
        public string $value,
    ) {}
}
