<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Prompts\Prompt;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class PromptGetResponse extends Response
{
    public function __construct(
        private Session $session,
        private Request $request,
        private Prompt $prompt,
        private array $arguments,
    ) {
        parent::__construct($session, $request);
    }

    public function attributes(): array
    {
        return [
            'result' => [
                'description' => $this->prompt->description(),
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            'type' => 'text',
                            'text' => $this->prompt->call(...$this->arguments),
                        ],
                    ],
                ],
            ],
        ];
    }
}
