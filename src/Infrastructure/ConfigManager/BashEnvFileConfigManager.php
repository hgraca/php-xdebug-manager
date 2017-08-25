<?php

namespace Hgraca\XdebugManager\Infrastructure\ConfigManager;

final class BashEnvFileConfigManager extends TextFileConfigManager
{
    private const KEY_PREFIX = 'export ';

    public function __construct()
    {
        parent::__construct(); // so we always set the = as assignment operator
    }

    public function set(string $filePath, string $key, string $value): void
    {
        parent::set($filePath, self::KEY_PREFIX . $key, "'$value'");
    }
}
