<?php

namespace Hgraca\XdebugManager\Core\Configuration\XdebugConfigManager;

final class XdebugBashrcManager extends XdebugConfigManagerAbstract
{
    /**
     * @param string|array $iniConfig
     */
    protected function writeIniConfig($iniConfig): void
    {
        file_put_contents($this->getContext()->getBashrcPath(), $iniConfig);
    }

    protected function readIniConfig(): array
    {
        return file($this->getContext()->getBashrcPath());
    }

    protected function createIniConfig(
        string $hostIp,
        string $hostname,
        string $xdebugOutputDir = '/tmp',
        string $xdebugIdeKey = 'PHPSTORM'
    ): string {

        return <<< INICONFIG
## XDEBUG_CONFIG_START ##

export XDEBUG_CONFIG='
xdebug.var_display_max_depth = 5
xdebug.var_display_max_data = 250
xdebug.var_display_max_children = 5
xdebug.max_nesting_level = 250
xdebug.cli_color = 1
xdebug.show_exception_trace = 0
xdebug.dump_globals = 1
xdebug.dump_once = 1
xdebug.show_local_vars = 1

xdebug.remote_autostart = 0
xdebug.remote_enable = 1              ; trigger: cookie:XDEBUG_SESSION=${xdebugIdeKey}
xdebug.remote_host = ${hostIp}
xdebug.remote_connect_back = 0        ; if enabled, xdebug.remote_host will be ignored
xdebug.remote_port = 9000
xdebug.remote_handler = dbgp
xdebug.remote_mode = req

xdebug.profiler_enable = 0
xdebug.profiler_enable_trigger = 1    ; trigger: cookie:XDEBUG_PROFILE=1
xdebug.profiler_output_dir = "${xdebugOutputDir}"
xdebug.profiler_output_name = "%H.profiler"

xdebug.auto_trace = 0
xdebug.trace_enable_trigger = 1       ; trigger: cookie:XDEBUG_TRACE=1
xdebug.trace_options = 0
xdebug.collect_params = 4
xdebug.collect_return = 1
xdebug.trace_format = 2
xdebug.trace_output_dir = "${xdebugOutputDir}"
xdebug.trace_output_name = "%H.trace"

xdebug.idekey = "${xdebugIdeKey}"'

export PHP_IDE_CONFIG='serverName=cli.${hostname}'

## XDEBUG_CONFIG_END ##
INICONFIG;
    }
}
