<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes;

use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ToolResultType
{
    public function __construct(
        public ResultType $resultType,
    ) {}
}
