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

    public function unSet(string $filePath, string $key): void
    {
        $config = $this->readConfig($filePath);

        unset($config[$key]);

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
        $configLineList = file_exists($filePath) ? file($filePath) : [];

        foreach ($configLineList as $configLine) {
            [$key, $value] = explode($this->assignmentOperator, $configLine);
            $config[$key] = trim($value);
        }

        return $config;
    }
}
