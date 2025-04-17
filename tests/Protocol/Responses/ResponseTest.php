<?php

use Aberdeener\LaravelMcpServer\Protocol\Responses\Response;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

it('returns the correct base response attributes', function () {
    $session = new Session;
    $request = new Request;
    $request->setId('1');
    $response = new class($session, $request) extends Response
    {
        public function attributes(): array
        {
            return ['result' => 'testResult'];
        }
    };

    expect($response->toArray())->toEqual([
        'jsonrpc' => '2.0',
        'id' => '1',
        'result' => 'testResult',
    ]);
});
