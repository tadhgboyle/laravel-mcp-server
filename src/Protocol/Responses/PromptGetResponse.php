<?php

namespace Aberdeener\LaravelMcpServer\Protocol\Responses;

use Aberdeener\LaravelMcpServer\Protocol\Error;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\RequestException;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Prompt;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\PromptRegistry;
use Aberdeener\LaravelMcpServer\Request;
use Aberdeener\LaravelMcpServer\Session;

class PromptGetResponse extends Response
{
    private Prompt $prompt;

    private array $arguments;

    public function __construct(
        private Session $session,
        private Request $request,
    ) {
        parent::__construct($session, $request);

        $promptName = $this->request->message()['params']['name'];
        $prompt = app(PromptRegistry::class)->getPrompt($promptName);

        if (! $prompt) {
            throw new RequestException(
                "Prompt not found: {$promptName}",
                Error::EntityNotFound,
            );
        }

        $this->prompt = $prompt;
        $this->arguments = $this->request->message()['params']['arguments'];
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
