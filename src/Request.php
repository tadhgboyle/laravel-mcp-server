<?php

namespace Aberdeener\LaravelMcpServer;

class Request
{
    private string $id;

    private array $message;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setMessage(array $message): void
    {
        $this->message = $message;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function message(): array
    {
        return $this->message;
    }
}
