<?php

namespace Aberdeener\LaravelMcpServer;

class Session
{
    private bool $initialized = false;

    public function setInitialized(): void
    {
        $this->initialized = true;
    }
}
