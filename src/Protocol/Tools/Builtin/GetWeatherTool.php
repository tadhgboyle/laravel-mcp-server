<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Tools\Builtin;

use Aberdeener\LaravelMcpServer\Protocol\Tools\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Tool;

class GetWeatherTool extends Tool
{
    public function __construct()
    {
        parent::__construct(
            name: 'get_weather',
            description: 'Get current weather information for a location',
        );
    }

    public function call(
        #[ParameterDescription(description: 'City name or zip code')]
        string $location
    ) {
        return "Current weather in {$location}:\nTemperature: 72°F\nConditions: Partly cloudy";
    }
}
