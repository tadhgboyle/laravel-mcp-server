<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

class PingResponse extends Response
{
    public function attributes(): array
    {

        return [
            'result' => (object) [],
        ];
    }
}
