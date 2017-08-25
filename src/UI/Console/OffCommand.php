<?php

namespace Hgraca\XdebugManager\UI\Console;

use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugDisabledException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OffCommand extends CommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('xdebug:off')
            ->setDescription('Turn XDebug off');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln('Disabling ...');
            $configurationService = $this->getConfigurationService();
            $configurationService->disable();
            $output->writeln('Done!');
        } catch (XdebugDisabledException $e) {
            $output->writeln('Xdebug is already disabled. Nothing to do!');
        }
    }
}
