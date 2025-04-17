<?php

namespace Aberdeener\LaravelMcpServer;

class Request
{
    private string $id;

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
