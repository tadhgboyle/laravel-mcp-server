<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\PromptRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class PromptListResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private PromptRegistry $promptRegistry,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'prompts' => $this->promptRegistry->allPrompts(),
            ],
        ];
    }
}
