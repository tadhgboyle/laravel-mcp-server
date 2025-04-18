<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Exceptions;

use Illuminate\Support\Str;

class ToolAttributeMissingException extends LaravelMcpServerException
{
    public function __construct(string $attribute)
    {
        $attribute = Str::afterLast($attribute, '\\');
        parent::__construct("The {$attribute} attribute is missing.");
    }
}
