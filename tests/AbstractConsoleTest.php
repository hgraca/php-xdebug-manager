<?php

namespace Hgraca\XdebugManager\Test;

use Cilex\Application;
use PHPUnit\Framework\TestCase;

abstract class AbstractConsoleTest extends TestCase
{
    const CONTAINER_PHP_PECL = 'php_pecl';

    const XDEBUG_INI_PATH = [
        self::CONTAINER_PHP_PECL => '/usr/local/etc/php/conf.d/xdebug.ini',
    ];

    protected function createConsole(): Application
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        return require __DIR__ . '/../src/console.php';
    }

    protected function runCommand(string $container, string $command): string
    {
        return exec("docker exec -it $container bash -ic 'bin/console $command'");
    }

    protected function getPhpStatus(string $container): string
    {
        return exec("docker exec -it $container bash -ic 'php -v'");
    }

    protected function getPhpConfig(string $container): string
    {
        return exec("docker exec -it $container bash -ic \"php -r 'phpinfo();'\"");
    }

    protected function getXdebugConfig(string $container): string
    {
        $xdebugIniPath = self::XDEBUG_INI_PATH[$container];

        return exec("docker exec -it $container bash -ic 'cat $xdebugIniPath'");
    }

    protected function getIdeConfig(string $container): string
    {
        return exec("docker exec -it $container bash -ic 'echo \$PHP_IDE_CONFIG'");
    }

    public function containersProvider(): array
    {
        return [
            [self::CONTAINER_PHP_PECL],
        ];
    }
}
