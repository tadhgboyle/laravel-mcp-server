<?php

namespace Aberdeener\LaravelMcpServer\Protocol;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

abstract class Response
{
    public function __construct(
        private Session $session,
        private Request $request,
    ) {}

    abstract public function attributes(): array;

    final private function baseAttributes(): array
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
