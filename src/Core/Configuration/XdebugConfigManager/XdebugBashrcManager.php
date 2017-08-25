<?php

namespace Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager;

use Hgraca\XdebugManager\Core\Context;
use Hgraca\XdebugManager\Infrastructure\ConfigManager\BashEnvFileConfigManager;

final class XdebugBashrcManager
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var BashEnvFileConfigManager
     */
    private $configManager;

    public function __construct(BashEnvFileConfigManager $configManager = null)
    {
        $this->context = new Context();
        $this->configManager = $configManager ?? new BashEnvFileConfigManager();
    }

    public function setProjectName(string $projectName): void
    {
        $this->set('PHP_IDE_CONFIG', "serverName=$projectName");
    }

    private function set(string $key, string $value): void
    {
        $this->configManager->set($this->context->getBashrcPath(), $key, $value);
    }
}
