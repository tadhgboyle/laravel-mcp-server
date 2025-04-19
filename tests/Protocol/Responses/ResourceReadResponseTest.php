<?php

use Aberdeener\LaravelMcpServer\Protocol\Resources\ResourceRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ResourceReadResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\DummyFileResource;

it('returns prompt response data', function () {
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
