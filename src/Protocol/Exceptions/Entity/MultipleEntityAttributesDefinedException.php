<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity;

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\LaravelMcpServerException;
use Illuminate\Support\Str;

class MultipleEntityAttributesDefinedException extends LaravelMcpServerException
{
    public function __construct(string $attribute)
    {
        $attribute = Str::afterLast($attribute, '\\');
        parent::__construct("Multiple {$attribute} attributes are defined.");
    }
}
