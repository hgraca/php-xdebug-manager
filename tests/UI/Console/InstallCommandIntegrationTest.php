<?php

namespace Hgraca\XdebugManager\Test\UI\Console;

use Hgraca\XdebugManager\Test\AbstractConsoleTest;

final class InstallCommandIntegrationTest extends AbstractConsoleTest
{
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
