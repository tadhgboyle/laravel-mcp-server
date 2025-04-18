<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity;

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\LaravelMcpServerException;

class InvalidEntityParameterTypeException extends LaravelMcpServerException
{
    public function __construct(string $message, string $parameterName, ?string $typeName = null)
    {
        $message = "$message (parameter: '$parameterName')";
        if ($typeName) {
            $message .= " (type: '$typeName')";
        }
        $message .= '.';

        parent::__construct($message);
    }
}
