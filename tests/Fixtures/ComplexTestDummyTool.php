<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;

#[ToolResultType(ResultType::Text)]
#[ToolDescription('A complex example tool')]
class ComplexTestDummyTool extends Tool
{
    public function call(
        #[ParameterDescription('The first argument')]
        int $arg1,
        #[ParameterDescription('The second argument')]
        ?string $arg2 = '',
        #[ParameterDescription('The third argument')]
        float $arg3 = 0.0,
        #[ParameterDescription('The fourth argument')]
        array $arg4 = [],
        #[ParameterDescription('The fifth argument')]
        bool $arg5 = false,
    ) {
        return func_get_args();
    }
}
