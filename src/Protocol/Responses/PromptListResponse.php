<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\PromptRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class PromptListResponse extends Response
{
    private PromptRegistry $promptRegistry;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $this->promptRegistry = app(PromptRegistry::class);
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
