<?php

namespace OranFry\Jars\CLI;

class ToolsConfig extends \Tools\Config
{
    public function includePath(): ?string
    {
        return 'vendor/oranfry/jars-cli';
    }

    public function router(): string
    {
        return CliRouter::class;
    }

    public function title(): string
    {
        return 'Jars CLI';
    }
}
