<?php

namespace Hgraca\XdebugManager\Core\Configuration\ConfigManager;

use Hgraca\XdebugManager\Core\Context;
use Hgraca\XdebugManager\Infrastructure\ConfigManager\BashEnvFileConfigManager;
use Hgraca\XdebugManager\Infrastructure\Shell\Bash;
use Hgraca\XdebugManager\Infrastructure\Shell\EnvironmentInterface;

final class EnvironmentManager
{
    private const PHP_IDE_CONFIG_KEY = 'PHP_IDE_CONFIG';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var BashEnvFileConfigManager
     */
    private $configManager;

    /**
     * @var EnvironmentInterface
     */
    private $environment;

    public function __construct(BashEnvFileConfigManager $configManager = null, EnvironmentInterface $environment = null)
    {
        $this->context = new Context();
        $this->configManager = $configManager ?? new BashEnvFileConfigManager();
        $this->environment = $environment ?? new Bash();
    }

    public function setProjectName(string $projectName): void
    {
        $value = "serverName=$projectName";
        $this->set(self::PHP_IDE_CONFIG_KEY, $value);
        $this->environment->setEnvVar(self::PHP_IDE_CONFIG_KEY, $value);
    }

    private function set(string $key, string $value): void
    {
        $this->configManager->set($this->context->getBashrcPath(), $key, $value);
    }
}
