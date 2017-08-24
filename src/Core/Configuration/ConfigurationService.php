<?php

namespace Hgraca\XdebugManager\Core\Configuration;

use Hgraca\Helper\StringHelper;
use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugDisabledException;
use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugEnabledException;
use Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager\XdebugBashrcManager;
use Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager\XdebugConfigManagerInterface;
use Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager\XdebugIniManager;
use Hgraca\XdebugManager\Core\Context;

final class ConfigurationService
{
    private const CMD_PHP_VERSION = 'php -v';
    private const CMD_PHP_VERSION_XDEBUG_ENABLED_MATCH = 'with Xdebug';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var XdebugIniManager
     */
    private $xdebugIniManager;

    /**
     * @var XdebugBashrcManager
     */
    private $xdebugBashrcManager;

    public function __construct(
        XdebugIniManager $xdebugIniManager,
        XdebugBashrcManager $xdebugBashrcManager
    ) {
        $this->context = new Context();
        $this->xdebugIniManager = $xdebugIniManager;
        $this->xdebugBashrcManager = $xdebugBashrcManager;
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

    public function resetConfig(string $hostIp, string $xdebugOutputDir, string $xdebugIdeKey): void
    {
        $this->xdebugIniManager->remove();
        $this->xdebugIniManager->create($hostIp, $xdebugOutputDir, $xdebugIdeKey);
    }

    public function isEnabled(): bool
    {
        return StringHelper::has(self::CMD_PHP_VERSION_XDEBUG_ENABLED_MATCH, exec(self::CMD_PHP_VERSION));
    }

    public function setDirective(string $key, string $value): void
    {
        $this->xdebugIniManager->set($key, $value);
    }

    public function setProjectName(string $projectName): void
    {
        $this->xdebugBashrcManager->setProjectName($projectName);
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
