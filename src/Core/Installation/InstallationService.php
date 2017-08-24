<?php

namespace Hgraca\XdebugManager\Core\Installation;

use Hgraca\XdebugManager\Core\Context;
use Hgraca\XdebugManager\Core\Installation\Exception\XdebugInstalledException;
use Hgraca\XdebugManager\Core\Installation\Exception\XdebugNotInstalledException;
use Hgraca\XdebugManager\Infrastructure\PackageManager\PackageManagerInterface;

final class InstallationService
{
    /**
     * @var PackageManagerInterface
     */
    private $packageManager;

    public function __construct()
    {
        $this->packageManager = (new Context())->getPackageManager();
    }

    /**
     * @throws XdebugInstalledException
     */
    public function install(): void
    {
        if ($this->packageManager->isInstalled()) {
            throw new XdebugInstalledException();
        }

        $this->packageManager->install();
    }

    /**
     * @throws XdebugNotInstalledException
     */
    public function uninstall(): void
    {
        if (!$this->packageManager->isInstalled()) {
            throw new XdebugNotInstalledException();
        }

        $this->packageManager->uninstall();
    }
}
