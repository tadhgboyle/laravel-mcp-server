<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolName;

#[ToolName('second_dummy')]
#[ToolDescription('Another example tool')]
class NamedDummyTool extends TestDummyTool {}
