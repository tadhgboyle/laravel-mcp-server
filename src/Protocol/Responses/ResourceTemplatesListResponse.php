<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Resources\Templates\ResourceTemplateRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ResourceTemplatesListResponse extends Response
{
    private ResourceTemplateRegistry $resourceTemplateRegistry;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $this->resourceTemplateRegistry = app(ResourceTemplateRegistry::class);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'resourceTemplates' => $this->resourceTemplateRegistry->allResourceTemplates(),
            ],
        ];
    }
}
