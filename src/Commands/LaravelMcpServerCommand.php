<?php

namespace Aberdeener\LaravelMcpServer\Commands;

use Illuminate\Console\Command;

class LaravelMcpServerCommand extends Command
{
    public $signature = 'laravel-mcp-server';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
