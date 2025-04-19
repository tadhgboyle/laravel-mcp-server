<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Resources\ResourceRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ResourceListResponse extends Response
{
    private ResourceRegistry $resourceRegistry;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $this->resourceRegistry = app(ResourceRegistry::class);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'resources' => $this->resourceRegistry->allResources(),
            ],
        ];
    }
}
