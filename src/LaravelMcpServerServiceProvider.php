<?php

namespace Aberdeener\LaravelMcpServer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Aberdeener\LaravelMcpServer\Commands\LaravelMcpServerCommand;

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
            ->hasViews()
            ->hasMigration('create_laravel_mcp_server_table')
            ->hasCommand(LaravelMcpServerCommand::class);
    }
}
