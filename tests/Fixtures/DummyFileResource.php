<?php

namespace Aberdeener\LaravelMcpServer\Tests\Fixtures;

use Aberdeener\LaravelMcpServer\Protocol\Resources\FileResource;
use SplFileInfo;

class DummyFileResource extends FileResource
{
    public function __construct()
    {
        $file = new SplFileInfo(__DIR__.'/DummyFileResource.php');

        parent::__construct(
            "file://{$file->getRealPath()}",
            $file->getRealPath(),
            "A PHP file {$file->getRealPath()}",
        );
    }
}
