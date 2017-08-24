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
            $output->writeln('Enabling ...');
            $configurationService = $this->getConfigurationService();
            $configurationService->enable();
        } catch (XdebugDisabledException $e) {
            $output->writeln('Xdebug is already disabled. Nothing to do!');
            exit;
        }

        $output->writeln('Done!');
    }
}
