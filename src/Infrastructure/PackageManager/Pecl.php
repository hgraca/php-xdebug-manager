<?php

namespace Hgraca\XdebugManager\Infrastructure\PackageManager;

use Hgraca\Helper\StringHelper;

final class Pecl implements PackageManagerInterface
{
    const NAME = 'pecl';
    private const CMD_INSTALL = 'pecl install xdebug';
    private const CMD_UNINSTALL = 'pecl uninstall xdebug';
    private const CMD_IS_INSTALLED_MATCH = 'xdebug';
    private const CMD_IS_INSTALLED = 'pecl list | grep ' . self::CMD_IS_INSTALLED_MATCH;

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
