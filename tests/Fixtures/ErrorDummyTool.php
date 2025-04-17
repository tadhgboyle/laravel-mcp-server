<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolName;
use Exception;

#[ToolName('error_dummy')]
#[ToolDescription('This tool always raises an error')]
class ErrorDummyTool extends TestDummyTool
{
    public function call(...$arguments): array
    {
        throw new Exception('An error occurred');
    }
}
