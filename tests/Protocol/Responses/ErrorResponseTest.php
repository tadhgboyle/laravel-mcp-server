<?php

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

it('returns the correct error code and message', function () {
    $session = new Session;
    $request = new Request;
    $message = 'Method not found';
    $error = Error::MethodNotFound;

    $response = new Aberdeener\LaravelMcpServer\Protocol\Responses\ErrorResponse($session, $request, $message, $error);

    expect($response->attributes())->toEqual([
        'error' => [
            'code' => $error->value,
            'message' => $message,
        ],
    ]);
});
