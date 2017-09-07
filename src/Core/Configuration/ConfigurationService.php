<?php

namespace Hgraca\XdebugManager\Core\Configuration;

use Hgraca\Helper\StringHelper;
use Hgraca\XdebugManager\Core\Configuration\ConfigManager\EnvironmentManager;
use Hgraca\XdebugManager\Core\Configuration\ConfigManager\IniManager;
use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugDisabledException;
use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugEnabledException;
use Hgraca\XdebugManager\Core\Context;
use Hgraca\XdebugManager\Infrastructure\Php\LinuxPhpManager;
use Hgraca\XdebugManager\Infrastructure\Php\PhpManagerInterface;

final class ConfigurationService
{
    private const CMD_PHP_VERSION_XDEBUG_ENABLED_MATCH = 'with Xdebug';
    const DEFAULT_HOST_IP = '172.25.0.1';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var IniManager
     */
    private $xdebugIniManager;

    /**
     * @var EnvironmentManager
     */
    private $xdebugBashrcManager;

    /**
     * @var PhpManagerInterface
     */
    private $phpManager;

    public function __construct(
        IniManager $xdebugIniManager,
        EnvironmentManager $xdebugBashrcManager,
        PhpManagerInterface $phpManager = null
    ) {
        $this->context = new Context();
        $this->xdebugIniManager = $xdebugIniManager;
        $this->xdebugBashrcManager = $xdebugBashrcManager;
        $this->phpManager = $phpManager ?? new LinuxPhpManager();
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
        if (!$this->isEnabled()) {
            throw new XdebugDisabledException();
        }

        $this->disableXdebugModule();
    }

    public function resetConfig(string $xdebugOutputDir, string $xdebugIdeKey, string $host = null): void
    {
        $this->xdebugIniManager->remove();
        $this->xdebugIniManager->create($xdebugOutputDir, $xdebugIdeKey, $host ?? $this->guessHost());
    }

    public function isEnabled(): bool
    {
        return StringHelper::has(self::CMD_PHP_VERSION_XDEBUG_ENABLED_MATCH, $this->phpManager->getPhpStatus());
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
        $this->xdebugIniManager->set('zend_extension', 'xdebug.so');
    }

    private function disableXdebugModule(): void
    {
        $this->xdebugIniManager->unSet('zend_extension');
    }

    private function guessHost()
    {
        return exec('/sbin/ip route|awk \'/default/ { print $3 }\'') ?? self::DEFAULT_HOST_IP;
    }
}
