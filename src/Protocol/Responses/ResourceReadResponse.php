<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\RequestException;
use Aberdeener\LaravelMcpServer\Protocol\Resources\Resource;
use Aberdeener\LaravelMcpServer\Protocol\Resources\ResourceRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ResourceReadResponse extends Response
{
    private Resource $resource;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $resourceUri = $this->request->message()['params']['uri'];
        $resource = app(ResourceRegistry::class)->getResource($resourceUri);

        if (! $resource) {
            throw new RequestException(
                "Resource not found: {$resourceUri}",
                Error::ResourceNotFound,
            );
        }

        $this->resource = $resource;
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'contents' => [
                    [
                        'uri' => $this->resource->uri(),
                        'mimeType' => $this->resource->mimeType()->value,
                        'text' => $this->resource->call(),
                    ],
                ],
            ],
        ];
    }
}
