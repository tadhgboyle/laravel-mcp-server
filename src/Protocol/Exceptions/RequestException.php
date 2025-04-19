<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Exceptions;

use Aberdeener\LaravelMcpServer\Protocol\Error;

class RequestException extends LaravelMcpServerException
{
    public Error $error;

    public function __construct(string $message, Error $error)
    {
        parent::__construct($message);
        $this->error = $error;
    }
}
