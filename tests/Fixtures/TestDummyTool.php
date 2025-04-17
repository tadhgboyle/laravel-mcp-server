<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;

#[ToolResultType(ResultType::Text)]
#[ToolDescription('An example tool')]
class TestDummyTool extends Tool
{
    public function call(
        #[ParameterDescription('The first argument')]
        int $arg1,
        #[ParameterDescription('The second argument')]
        int $arg2 = 0
    ) {
        return $arg1 + $arg2;
    }
}
