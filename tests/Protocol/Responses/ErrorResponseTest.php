<?php

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

it('returns the correct error code and message', function () {
    $session = new Session;
    $request = new Request;
    $error = 'Method not found';
    $code = -32601;

    $response = new Aberdeener\LaravelMcpServer\Protocol\Responses\ErrorResponse($session, $request, $error, $code);

    expect($response->attributes())->toEqual([
        'error' => [
            'code' => $code,
            'message' => $error,
        ],
    ]);
});
