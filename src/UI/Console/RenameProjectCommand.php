<?php

namespace Hgraca\XdebugManager\UI\Console;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RenameProjectCommand extends CommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('xdebug:rename-project')
            ->setDescription('Rename the project')
            ->addArgument('projectName', InputArgument::REQUIRED, 'The project name, used by the IDE')
            ->addUsage('bin/console xdebug:rename-project bladibla');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Renaming ...');
        $configurationService = $this->getConfigurationService();
        $configurationService->setProjectName($input->getArgument('projectName'));
        $output->writeln('Done!');
    }
}
