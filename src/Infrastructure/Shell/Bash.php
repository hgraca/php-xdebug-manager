<?php

namespace Hgraca\XdebugManager\Infrastructure\Shell;

final class Bash implements EnvironmentInterface
{
    public function setEnvVar(string $key, string $value): string
    {
        return exec("export $key='$value'");
    }
}
