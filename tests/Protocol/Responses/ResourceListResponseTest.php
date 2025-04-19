<?php

use Aberdeener\LaravelMcpServer\Protocol\Resources\ResourceRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Responses\ResourceListResponse;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;
use Aberdeener\LaravelMcpServer\Tests\Fixtures\DummyFileResource;

it('returns the list of prompts in their array format', function () {
    $promptRegistry = app(ResourceRegistry::class);
    $promptRegistry->registerResource(new DummyFileResource);

    $response = new ResourceListResponse(new Session, new Request);
    $attributes = $response->attributes();

    $expectedFile = new SplFileInfo(__DIR__.'/../../Fixtures/DummyFileResource.php');

    expect($attributes)->toBe([
        'result' => [
            'resources' => [
                [
                    'uri' => 'file://'.$expectedFile->getRealPath(),
                    'name' => $expectedFile->getRealPath(),
                    'description' => 'A PHP file '.$expectedFile->getRealPath(),
                    'mimeType' => 'text/plain',
                ],
            ],
        ],
    ]);
});
