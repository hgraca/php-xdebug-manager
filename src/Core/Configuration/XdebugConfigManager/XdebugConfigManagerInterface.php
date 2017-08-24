<?php

namespace Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager;

interface XdebugConfigManagerInterface
{
    const QUOTED_DIRECTIVE_LIST = [
        'xdebug.profiler_output_dir',
        'xdebug.profiler_output_name',
        'xdebug.trace_output_dir',
        'xdebug.trace_output_name',
        'xdebug.idekey'
    ];

    public function exists(): bool;

    public function create(
        string $hostIp,
        string $xdebugOutputDir,
        string $xdebugIdeKey,
        string $hostname = null
    ): void;

    public function remove(): void;

    public function set(string $key, string $value): void;
}
