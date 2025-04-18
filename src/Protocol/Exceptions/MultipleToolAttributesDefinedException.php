<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Exceptions;

use Illuminate\Support\Str;

class MultipleToolAttributesDefinedException extends LaravelMcpServerException
{
    public function __construct(string $attribute)
    {
        $attribute = Str::afterLast($attribute, '\\');
        parent::__construct("Multiple {$attribute} attributes are defined.");
    }
}
