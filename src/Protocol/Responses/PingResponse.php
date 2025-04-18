<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use stdClass;

class PingResponse extends Response
{
    public function attributes(): array
    {

        return [
            'result' => new stdClass,
        ];
    }
}
