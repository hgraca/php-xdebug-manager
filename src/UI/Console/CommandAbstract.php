<?php

namespace Hgraca\XdebugManager\UI\Console;

use Cilex\Provider\Console\Command;
use Hgraca\XdebugManager\Core\Configuration\ConfigurationService;
use Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager\XdebugBashrcManager;
use Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager\XdebugIniManager;
use Hgraca\XdebugManager\Core\Installation\InstallationService;

abstract class CommandAbstract extends Command
{
    protected function getInstallationService(): InstallationService
    {
        return new InstallationService();
    }

    protected function getConfigurationService(): ConfigurationService
    {
        return new ConfigurationService(new XdebugIniManager(), new XdebugBashrcManager());
    }
}
