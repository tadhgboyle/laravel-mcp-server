<?php

namespace Aberdeener\LaravelMcpServer\Protocol;

enum Error: int
{
    case MethodNotFound = -32601;
    case EntityNotFound = -32602;
    case ResourceNotFound = -32603;
}
