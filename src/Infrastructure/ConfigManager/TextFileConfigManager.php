<?php

namespace Hgraca\XdebugManager\Infrastructure\ConfigManager;

use Hgraca\Helper\StringHelper;

class TextFileConfigManager implements ConfigManagerInterface
{
    /**
     * @var string
     */
    protected $assignmentOperator;

    public function __construct(string $assignmentOperator = '=')
    {
        $this->assignmentOperator = $assignmentOperator;
    }

    public function set(string $filePath, string $keyToSet, string $value): void
    {
        $iniConfigLines = $this->readConfig($filePath);

        $found = false;
        foreach ($iniConfigLines as $key => $line) {
            // we go through all lines so that if the same directive is present several times, they are all updated
            if (StringHelper::hasBeginning($keyToSet, $line)) {
                $iniConfigLines[$key] = $this->createConfigLine($keyToSet, $value);
                $found = true;
            }
        }

        if (!$found) {
            $iniConfigLines[] = $this->createConfigLine($keyToSet, $value);
        }

        $this->writeConfig($filePath, $iniConfigLines);
    }

    public function unSet(string $filePath, string $keyToUnSet): void
    {
        $iniConfigLines = $this->readConfig($filePath);

        foreach ($iniConfigLines as $key => $line) {
            // we go through all lines so that if the same directive is present several times, they are all updated
            if (StringHelper::hasBeginning($keyToUnSet, $line)) {
                unset($iniConfigLines[$key]);
            }
        }

        $this->writeConfig($filePath, $iniConfigLines);
    }

    /**
     * @param array $iniConfig
     */
    public function writeConfig(string $filePath, array $config): void
    {
        file_put_contents($filePath, $config);
    }

    private function readConfig(string $filePath): array
    {
        return file($filePath);
    }

    private function createConfigLine(string $keyToSet, string $value): string
    {
        return $keyToSet . $this->assignmentOperator . $value;
    }
}
