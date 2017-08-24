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
            ->addArgument('hostIp', InputArgument::REQUIRED, 'The host IP')
            ->addArgument('xdebugOutputDir', InputArgument::OPTIONAL, 'Xdebug output dir for the profiler', '/tmp')
            ->addArgument('xdebugIdeKey', InputArgument::OPTIONAL, 'IDE key so the IDE picks it up', 'PHPSTORM')
            ->addArgument('hostname', InputArgument::OPTIONAL, 'The server hostname, used when debugging CLI', '');
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
            $output->writeln('Xdebug is already installed. Nothing to do!');
            exit;
        }

        try {
            $output->writeln('Configuring ...');
            $configurationService = $this->getConfigurationService();
            $configurationService->enable();
            $configurationService->resetConfig(
                $input->getArgument('hostIp'),
                $input->getArgument('xdebugOutputDir'),
                $input->getArgument('xdebugIdeKey'),
                $input->getArgument('hostname')
            );
        } catch (XdebugEnabledException $e) {
            $output->writeln('Xdebug is already enabled. Nothing to do!');
            exit;
        }

        $output->writeln('Done!');
    }
}
