<?php

namespace Hgraca\XdebugManager\UI\Console;

use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugEnabledException;
use Hgraca\XdebugManager\Core\Installation\Exception\XdebugInstalledException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InstallCommand extends CommandAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('xdebug:install')
            ->setDescription('Install XDebug.')
            ->addArgument('projectName', InputArgument::OPTIONAL, 'The project name, used by the IDE', 'project')
            ->addArgument('xdebugOutputDir', InputArgument::OPTIONAL, 'Xdebug output dir for the profiler', '/tmp')
            ->addArgument('xdebugIdeKey', InputArgument::OPTIONAL, 'IDE key so the IDE picks it up', 'PHPSTORM')
            ->addArgument(
                'host',
                InputArgument::OPTIONAL,
                'Host where the debug client is running, you can either use a host name, IP address,'
                . ' or \'unix:///path/to/sock\' for a Unix domain socket.'
                . 'We only need to use this if the educated guess is failing.'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $installationService = $this->getInstallationService();
            $output->writeln('Installing ...');
            $installationService->install();
        } catch (XdebugInstalledException $e) {
            $output->writeln('Xdebug is already installed. Moving on...');
        }

        try {
            $output->writeln('Configuring ...');
            $configurationService = $this->getConfigurationService();
            $configurationService->enable();
            $configurationService->resetConfig(
                $input->getArgument('xdebugOutputDir'),
                $input->getArgument('xdebugIdeKey'),
                $input->getArgument('host')
            );
            $configurationService->setProjectName($input->getArgument('projectName'));
            $this->getPhpManager()->restartPhpFpm();
        } catch (XdebugEnabledException $e) {
            $output->writeln('Xdebug is already enabled. Nothing to do!');
        }

        $output->writeln('Done!');
    }
}
