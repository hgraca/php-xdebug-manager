<?php

namespace Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager;

use Hgraca\Helper\StringHelper;
use Hgraca\XdebugManager\Core\Context;

abstract class XdebugConfigManagerAbstract implements XdebugConfigManagerInterface
{
    /**
     * @var Context
     */
    private $context;

    public function __construct()
    {
        $this->context = new Context();
    }

    public function exists(): bool
    {
        return file_exists($this->context->getXdebugIniPath());
    }

    public function create(
        string $hostIp,
        string $xdebugOutputDir,
        string $xdebugIdeKey,
        string $hostname = null
    ): void {
        $this->writeIniConfig($this->createIniConfig($hostIp, $xdebugOutputDir, $xdebugIdeKey));
    }

    public function remove(): void
    {
        foreach ($this->context->getXdebugIniLinkingPathList() as $linkingPath) {
            unlink($linkingPath);
        }
        unlink($this->context->getXdebugIniPath());
    }

    public function set(string $key, string $value): void
    {
        $iniConfigLines = $this->readIniConfig();

        // we go through all lines so that if the same directive is present several times, they are all updated
        foreach ($iniConfigLines as $key => $line) {
            if (StringHelper::hasBeginning($key, $line)) {
                $iniConfigLines[$key] = $key . ' = ' . $this->quoteValue($key, $value);
            }
        }

        $this->writeIniConfig($iniConfigLines);
    }

    /**
     * @param string|array $iniConfig
     */
    abstract protected function writeIniConfig($iniConfig): void;

    abstract protected function readIniConfig(): array;

    abstract protected function createIniConfig(
        string $hostIp,
        string $hostname,
        string $xdebugOutputDir = '/tmp',
        string $xdebugIdeKey = 'PHPSTORM'
    ): string;

    protected function getContext(): Context
    {
        return $this->context;
    }

    private function quoteValue(string $key, string $value)
    {
        return in_array($key, self::QUOTED_DIRECTIVE_LIST)
            ? '"' . $value . '"'
            : $value;
    }
}
