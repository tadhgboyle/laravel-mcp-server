<?php

use Aberdeener\LaravelMcpServer\Protocol\Tools\Builtin\GetWeatherTool;

it('can call the tool with array', function () {
    $tool = new GetWeatherTool;
    $result = $tool->call(...['location' => 'Ottawa']);
    expect($result)->toBe('Current weather in Ottawa: Temperature: 72°F, Conditions: Partly cloudy');
});

it('can call the tool with param', function () {
    $tool = new GetWeatherTool;
    $result = $tool->call('Kelowna');
    expect($result)->toBe('Current weather in Kelowna: Temperature: 72°F, Conditions: Partly cloudy');
});
