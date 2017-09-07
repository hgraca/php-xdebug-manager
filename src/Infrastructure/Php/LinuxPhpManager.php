<?php

namespace Hgraca\XdebugManager\Infrastructure\Php;

final class LinuxPhpManager implements PhpManagerInterface
{
    public function restartPhpFpm(): void
    {
        exec('pkill -HUP php-fpm');
    }

    public function getPhpStatus(): string
    {
        return shell_exec('php -v');
    }
}
