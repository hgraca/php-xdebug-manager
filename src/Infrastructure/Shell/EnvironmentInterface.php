<?php

namespace Hgraca\XdebugManager\Infrastructure\Shell;

interface EnvironmentInterface
{
    public function setEnvVar(string $key, string $value): string;
}
