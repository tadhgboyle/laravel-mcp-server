<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class ErrorResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private string $message,
        private Error $error,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        return [
            'error' => [
                'code' => $this->error->value,
                'message' => $this->message,
            ],
        ];
    }
}
