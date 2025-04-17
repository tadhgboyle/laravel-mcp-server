<?php

use Aberdeener\LaravelMcpServer\Request;

it('can set and get the request ID', function () {
    $request = new Request;
    $request->setId('12345');

    expect($request->id())->toBe('12345');
});
