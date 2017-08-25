<?php

namespace Hgraca\XdebugManager\Infrastructure\ConfigManager;

final class KeyValueFileConfigManager implements ConfigManagerInterface
{
    /**
     * @var string
     */
    private $assignmentOperator;

    public function __construct(string $assignmentOperator = ' = ')
    {
        $this->assignmentOperator = $assignmentOperator;
    }

    public function set(string $filePath, string $key, string $value): void
    {
        $config = $this->readConfig($filePath);

        $config[$key] = $value;

        $this->writeConfig($filePath, $config);
    }

    /**
     * @param array $iniConfig in the format ['directive' => $value]
     */
    public function writeConfig(string $filePath, array $config): void
    {
        $configLineList = [];
        foreach ($config as $key => $value) {
            $configLineList[] = $key . $this->assignmentOperator . $value . "\n";
        }
        file_put_contents($filePath, $configLineList);
    }

    private function readConfig(string $filePath): array
    {
        $config = [];
        $configLineList = file($filePath);

        if ($configLineList === false) {
            return $config;
        }

        foreach ($configLineList as [$key, $value]) {
            $config[$key] = $value;
        }

        return $config;
    }
}