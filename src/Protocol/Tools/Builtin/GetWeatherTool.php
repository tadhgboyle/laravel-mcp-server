<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools\Builtin;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolName;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ToolResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ResultType;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;

#[ToolName(name: 'get_weather')]
#[ToolDescription(description: 'Get current weather information')]
#[ToolResultType(resultType: ResultType::Text)]
class GetWeatherTool extends Tool
{
    public function call(
        #[ParameterDescription(description: 'City name or zip code')]
        string $location
    ) {
        return "Current weather in {$location}: Temperature: 72°F, Conditions: Partly cloudy";
    }
}
