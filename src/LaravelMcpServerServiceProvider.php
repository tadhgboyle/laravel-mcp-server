<?php

namespace Aberdeener\LaravelMcpServer;

use Aberdeener\LaravelMcpServer\Commands\LaravelMcpServerCommand;
use Aberdeener\LaravelMcpServer\Protocol\Tools\Builtin\GetWeatherTool;
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
            ->hasConfigFile()
            ->hasCommand(LaravelMcpServerCommand::class);

        $this->app->singleton(ToolRegistry::class);

        $toolRegistry = $this->app->make(ToolRegistry::class);

        $toolRegistry->registerTool(
            new GetWeatherTool()
        );
    }
}
