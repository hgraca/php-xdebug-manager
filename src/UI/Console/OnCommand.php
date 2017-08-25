<?php

namespace Hgraca\XdebugManager\UI\Console;

use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugEnabledException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OnCommand extends CommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('xdebug:on')
            ->setDescription('Turn XDebug on');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln('Enabling ...');
            $configurationService = $this->getConfigurationService();
            $configurationService->enable();
            $output->writeln('Done!');
        } catch (XdebugEnabledException $e) {
            $output->writeln('Xdebug is already enabled. Nothing to do!');
        }
    }
}
