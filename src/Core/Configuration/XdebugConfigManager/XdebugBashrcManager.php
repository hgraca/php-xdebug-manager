<?php

namespace Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager;

use Hgraca\XdebugManager\Core\Context;
use Hgraca\XdebugManager\Infrastructure\ConfigManager\TextFileConfigManager;

final class XdebugBashrcManager
{
    private const PHP_IDE_CONFIG = 'export PHP_IDE_CONFIG';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var TextFileConfigManager
     */
    private $configManager;

    public function __construct(TextFileConfigManager $configManager = null)
    {
        $this->context = new Context();
        $this->configManager = $configManager ?? new TextFileConfigManager();
    }

    public function setProjectName(string $projectName): void
    {
        $this->set(self::PHP_IDE_CONFIG, "'serverName=$projectName'");
    }

    private function set(string $key, string $value): void
    {
        $this->configManager->set($this->context->getBashrcPath(), $key, $value);
    }
}
