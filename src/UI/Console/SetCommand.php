<?php

namespace Hgraca\XdebugManager\UI\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SetCommand extends CommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('xdebug:set')
            ->setDescription('Set an XDebug directive')
            ->addArgument('key', InputArgument::REQUIRED)
            ->addArgument('val', InputArgument::REQUIRED);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Enabling ...');

        $configurationService = $this->getConfigurationService();
        $configurationService->setDirective(
            $input->getArgument('key'),
            $input->getArgument('val')
        );

        $output->writeln('Done!');
    }
}
