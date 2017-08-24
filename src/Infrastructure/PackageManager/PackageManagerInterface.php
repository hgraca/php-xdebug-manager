<?php

namespace Hgraca\XdebugManager\Infrastructure\PackageManager;

interface PackageManagerInterface
{
    public function install(): void;

    public function uninstall(): void;

    public function isInstalled(): bool;

    public function __toString(): string;
}
