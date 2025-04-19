<?php

namespace Aberdeener\LaravelMcpServer;

use Aberdeener\LaravelMcpServer\Commands\LaravelMcpServerCommand;
use Aberdeener\LaravelMcpServer\Protocol\Prompts\PromptRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Resources\ResourceRegistry;
use Aberdeener\LaravelMcpServer\Protocol\Tools\ToolRegistry;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelMcpServerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mcp-server')
            ->hasCommand(LaravelMcpServerCommand::class);

        $this->app->singleton(ToolRegistry::class);
        $this->app->singleton(PromptRegistry::class);
        $this->app->singleton(ResourceRegistry::class);
    }
}
