# laravel-mcp-server

[![Latest Version on Packagist](https://img.shields.io/packagist/v/aberdeener/laravel-mcp-server.svg?style=flat-square)](https://packagist.org/packages//laravel-mcp-server)
[![Total Downloads](https://img.shields.io/packagist/dt/tadhgboyle/laravel-mcp-server.svg?style=flat-square)](https://packagist.org/packages/tadhgboyle/laravel-mcp-server)

This package provides a Model Context Protocol (MCP) server for Laravel applications. It allows you to create and register tools and prompts that can be used in conjunction with the MCP protocol.

The MCP protocol is a standard for building tools that can interact with language models, enabling developers to create powerful applications that leverage the capabilities of these models.

Read more about the MCP protocol https://modelcontextprotocol.io.

## Installation

You can install the package via composer:

```bash
composer require aberdeener/laravel-mcp-server
```

## Usage

```php
// Within your AppServiceProvider.php

use Aberdeener\LaravelMcpServer\ToolRegistry;
use Aberdeener\LaravelMcpServer\PromptRegistry;

public function boot(): void
{
    // ...

    app(ToolRegistry::class)->registerTool(new GetWeatherTool);
    app(PromptRegistry::class)->registerPrompt(new ReviewPhpCode);
}
```

Experiment with the official [MCP inspector](https://github.com/modelcontextprotocol/inspector):

```bash
npx @modelcontextprotocol/inspector php /path/to/your/laravel/app/artisan mcp:stdio
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [tadhgboyle](https://github.com/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
