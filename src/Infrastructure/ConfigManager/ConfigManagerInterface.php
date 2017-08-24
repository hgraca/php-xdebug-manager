<?php

namespace Hgraca\XdebugManager\Infrastructure\ConfigManager;

interface ConfigManagerInterface
{
    public function set(string $filePath, string $key, string $value): void;

    /**
     * @param array $config in the format ['directive' => $value]
     */
    public function writeConfig(string $filePath, array $config): void;
}
