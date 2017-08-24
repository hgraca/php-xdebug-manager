<?php

namespace Hgraca\XdebugManager\Core\Configuration;

use Hgraca\Helper\StringHelper;
use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugDisabledException;
use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugEnabledException;
use Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager\XdebugConfigManagerInterface;
use Hgraca\XdebugManager\Core\Context;

final class ConfigurationService
{
    private const CMD_PHP_VERSION = 'php -v';
    private const CMD_PHP_VERSION_XDEBUG_ENABLED_MATCH = 'with Xdebug';

    /**
     * @var XdebugConfigManagerInterface[]
     */
    private $xdebugConfigManagerList;

    /**
     * @var Context
     */
    private $context;

    public function __construct(
        XdebugConfigManagerInterface ... $xdebugConfigManagerList
    ) {
        $this->xdebugConfigManagerList = $xdebugConfigManagerList;
        $this->context = new Context();
    }

    /**
     * @throws XdebugEnabledException
     */
    public function enable(): void
    {
        if ($this->isEnabled()) {
            throw new XdebugEnabledException();
        }

        $this->enableXdebugModule();
    }

    public function disable(): void
    {
        if ($this->isEnabled()) {
            throw new XdebugDisabledException();
        }

        $this->disableXdebugModule();
    }

    public function resetConfig(string $hostIp, string $xdebugOutputDir, string $xdebugIdeKey, string $hostname): void
    {
        foreach ($this->xdebugConfigManagerList as $xdebugConfigManager) {
            $xdebugConfigManager->remove();
            $xdebugConfigManager->create($hostIp, $xdebugOutputDir, $xdebugIdeKey, $hostname);
        }
    }

    public function isEnabled(): bool
    {
        return StringHelper::has(self::CMD_PHP_VERSION_XDEBUG_ENABLED_MATCH, exec(self::CMD_PHP_VERSION));
    }

    public function setDirective(string $key, string $value): void
    {
        foreach ($this->xdebugConfigManagerList as $xdebugConfigManager) {
            $xdebugConfigManager->set($key, $value);
        }
    }

    private function enableXdebugModule(): void
    {
        $xdebugIniPath = $this->context->getXdebugIniPath();
        foreach ($this->context->getXdebugIniLinkingPathList() as $linkingPath) {
            link($xdebugIniPath, $linkingPath);
        }
    }

    private function disableXdebugModule(): void
    {
        foreach ($this->context->getXdebugIniLinkingPathList() as $linkingPath) {
            unlink($linkingPath);
        }
    }
}
