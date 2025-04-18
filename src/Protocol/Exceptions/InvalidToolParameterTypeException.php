<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Exceptions;

class InvalidToolParameterTypeException extends LaravelMcpServerException
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
