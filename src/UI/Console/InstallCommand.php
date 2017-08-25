<?php

namespace Hgraca\XdebugManager\UI\Console;

use Hgraca\XdebugManager\Core\Configuration\Exception\XdebugEnabledException;
use Hgraca\XdebugManager\Core\Installation\Exception\XdebugInstalledException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->addOption('projectName', 'p', InputOption::VALUE_OPTIONAL, 'The project name, used by the IDE', 'project')
            ->addOption('xdebugOutputDir', 'o', InputOption::VALUE_OPTIONAL, 'Xdebug output dir for the profiler', '/tmp')
            ->addOption('xdebugIdeKey', 'k', InputOption::VALUE_OPTIONAL, 'IDE key so the IDE picks it up', 'PHPSTORM')
            ->addOption(
                'host',
                'a',
                InputOption::VALUE_OPTIONAL,
                'Host where the debug client is running, you can either use a host name, IP address,'
                . ' or \'unix:///path/to/sock\' for a Unix domain socket.'
                . 'We only need to use this if the educated guess is failing.'
            )
            ->addUsage('bin/console xdebug:install --projectName project --xdebugOutputDir /tmp --xdebugIdeKey PHPSTORM --host localhost')
            ->addUsage('bin/console xdebug:install -pproject -o/tmp -kPHPSTORM -alocalhost');
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
                $input->getOption('xdebugOutputDir'),
                $input->getOption('xdebugIdeKey'),
                $input->getOption('host')
            );
            $configurationService->setProjectName($input->getOption('projectName'));
            $this->getPhpManager()->restartPhpFpm();
        } catch (XdebugEnabledException $e) {
            $output->writeln('Xdebug is already enabled. Nothing to do!');
        }

        $output->writeln('Done!');
    }
}
