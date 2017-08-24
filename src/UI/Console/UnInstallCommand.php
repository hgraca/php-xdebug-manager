<?php

namespace Hgraca\XdebugManager\UI\Console;

use Hgraca\XdebugManager\Core\Installation\Exception\XdebugNotInstalledException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UnInstallCommand extends CommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('xdebug:uninstall')
            ->setDescription('Uninstall XDebug');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $installationService = $this->getInstallationService();
            $output->writeln('Uninstalling ...');
            $installationService->uninstall();
        } catch (XdebugNotInstalledException $e) {
            $output->writeln('Xdebug is not installed. Nothing to do!');
        }
    }
}
