<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\ParameterDescription;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\PromptDescription;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Prompt;

#[PromptDescription('An example prompt')]
class TestDummyPrompt extends Prompt
{
    public function call(
        #[ParameterDescription('The first argument')]
        string $code1,
        #[ParameterDescription('The second argument')]
        string $code2 = '',
    ) {
        return "Please evaluate the following PHP code for style issues: {$code1}, {$code2}";
    }
}
