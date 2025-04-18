<?php

use Aberdeener\LaravelMcpServer\Protocol\Responses\PingResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

it('responds with an empty result', function () {
    $response = new PingResponse(
        new Session,
        new Request
    );

    expect(json_encode($response->attributes()))
        ->toBe('{"result":{}}');
});
