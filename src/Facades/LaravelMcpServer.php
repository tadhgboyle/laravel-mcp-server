<?php

namespace Aberdeener\LaravelMcpServer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Aberdeener\LaravelMcpServer\LaravelMcpServer
 */
class LaravelMcpServer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Aberdeener\LaravelMcpServer\LaravelMcpServer::class;
    }
}
