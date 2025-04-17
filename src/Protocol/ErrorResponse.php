<?php

namespace Aberdeener\LaravelMcpServer\Protocol;

use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ErrorResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private string $error,
        private int $code,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        return [
            'error' => [
                'code' => $this->code,
                'message' => $this->error,
            ],
        ];
    }
}
