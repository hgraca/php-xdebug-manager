<?php

namespace Hgraca\XdebugManager\Infrastructure\Php;

interface PhpManagerInterface
{
    public function restartPhpFpm(): void;

    public function getPhpStatus(): string;
}
