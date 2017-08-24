<?php

namespace Hgraca\XdebugManager\Infrastructure\PackageManager;

use Hgraca\Helper\StringHelper;

final class AptGet implements PackageManagerInterface
{
    const NAME = 'apt-get';
    private const CMD_INSTALL = 'apt-get -y install php-xdebug';
    private const CMD_UNINSTALL = 'apt-get -y purge php-xdebug';
    private const CMD_IS_INSTALLED_MATCH = 'install ok installed';
    private const CMD_IS_INSTALLED = 'dpkg-query -W --showformat=\'${Status}\n\' php-xdebug | grep "'
        . self::CMD_IS_INSTALLED_MATCH
        . '"';

    public function install(): void
    {
        exec(self::CMD_INSTALL);
    }

    public function uninstall(): void
    {
        exec(self::CMD_UNINSTALL);
    }

    public function isInstalled(): bool
    {
        return StringHelper::has(self::CMD_IS_INSTALLED_MATCH, exec(self::CMD_IS_INSTALLED));
    }

    public function __toString(): string
    {
        return self::NAME;
    }
}
