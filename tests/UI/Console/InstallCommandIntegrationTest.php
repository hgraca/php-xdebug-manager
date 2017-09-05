<?php

namespace Hgraca\XdebugManager\Test\UI\Console;

use Hgraca\XdebugManager\Test\AbstractConsoleTest;

final class InstallCommandIntegrationTest extends AbstractConsoleTest
{
    /**
     * @beforeClass
     */
    public static function beforeClass(): void
    {
        foreach (self::containersProvider() as [$container]) {
            self::destroyContainer($container);
            self::startContainer($container);
        }
    }

    /**
     * @afterClass
     */
    public static function afterClass(): void
    {
        foreach (self::containersProvider() as [$container]) {
            self::destroyContainer($container);
        }
    }

    /**
     * @test
     * @dataProvider containersProvider
     */
    public function defaultInstallationWorks(string $container): void
    {
        self::assertNotContains('with Xdebug', $this->getPhpStatus($container));

        $this->runCommand($container, 'xdebug:install');

        self::assertContains('with Xdebug', $this->getPhpStatus($container));
    }
}
