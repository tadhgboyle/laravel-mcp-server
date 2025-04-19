<?php

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\RequestException;
use Aberdeener\LaravelMcpServer\Protocol\Resources\ResourceRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ResourceReadResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\DummyFileResource;

it('raises an exception when resource not found', function () {
    $request = new Request;
    $request->setMessage([
        'params' => [
            'uri' => 'file://dummy.txt',
        ],
    ]);
    new ResourceReadResponse(new Session, $request);
})->throws(
    RequestException::class,
    'Resource not found: file://dummy.txt',
    Error::ResourceNotFound->value,
);

it('returns resource response data', function () {
    $resourceRegistry = app(ResourceRegistry::class);
    $resourceRegistry->registerResource(new DummyFileResource);

    $expectedFile = new SplFileInfo(__DIR__.'/../../Fixtures/DummyFileResource.php');

    $request = new Request;
    $request->setMessage([
        'params' => [
            'uri' => 'file://'.$expectedFile->getRealPath(),
        ],
    ]);

    $response = new ResourceReadResponse(new Session, $request);

    expect($response->attributes())->toEqual([
        'result' => [
            'contents' => [
                [
                    'uri' => 'file://'.$expectedFile->getRealPath(),
                    'mimeType' => 'text/plain',
                    'text' => file_get_contents($expectedFile->getRealPath()),
                ],
            ],
        ],
    ]);
});
