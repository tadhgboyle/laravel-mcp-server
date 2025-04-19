<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

abstract class Response
{
    public final const REQUEST_HANDLERS = [
        'initialize' => InitializeResponse::class,
        'ping' => PingResponse::class,
        'tools/list' => ToolListResponse::class,
        'tools/call' => ToolCallResponse::class,
        'prompts/list' => PromptListResponse::class,
        'prompts/get' => PromptGetResponse::class,
        'resources/list' => ResourceListResponse::class,
        'resources/read' => ResourceReadResponse::class,
        // 'resources/templates/list' => ResourceTemplateListResponse::class,
    ];

    public function __construct(
        private Session $session,
        private Request $request,
    ) {}

    abstract public function attributes(): array;

    private function baseAttributes(): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->request->id(),
        ];
    }

    final public function toArray(): array
    {
        return array_merge($this->baseAttributes(), $this->attributes());
    }
}
