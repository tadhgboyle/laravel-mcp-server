<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\PromptDescription;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\Attributes\PromptName;

#[PromptName('second_dummy')]
#[PromptDescription('Another example prompt')]
class NamedDummyPrompt extends TestDummyPrompt {}
