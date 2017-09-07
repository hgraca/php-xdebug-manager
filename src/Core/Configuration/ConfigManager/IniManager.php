<?php

namespace Hgraca\XdebugManager\Core\Configuration\ConfigManager;

use Hgraca\XdebugManager\Core\Context;
use Hgraca\XdebugManager\Infrastructure\ConfigManager\ConfigManagerInterface;
use Hgraca\XdebugManager\Infrastructure\ConfigManager\KeyValueFileConfigManager;

final class IniManager
{
    private const QUOTED_DIRECTIVE_LIST = [
        'xdebug.profiler_output_dir',
        'xdebug.profiler_output_name',
        'xdebug.trace_output_dir',
        'xdebug.trace_output_name',
        'xdebug.idekey',
        'xdebug.remote_host'
    ];

    /**
     * @var Context
     */
    private $context;

    /**
     * @var ConfigManagerInterface
     */
    private $configManager;

    public function __construct(ConfigManagerInterface $configManager = null)
    {
        $this->context = new Context();
        $this->configManager = $configManager ?? new KeyValueFileConfigManager();
    }

    public function exists(): bool
    {
        return file_exists($this->context->getXdebugIniPath());
    }

    public function create(
        string $xdebugOutputDir,
        string $xdebugIdeKey,
        string $host
    ): void {
        $this->configManager->writeConfig(
            $this->getContext()->getXdebugIniPath(),
            $this->createIniConfig($host, $xdebugOutputDir, $xdebugIdeKey)
        );

        foreach ($this->context->getXdebugIniLinkingPathList() as $linkingPath) {
            if (file_exists($linkingPath)) {
                unlink($linkingPath);
            }
            link($this->context->getXdebugIniPath(), $linkingPath);
        }
    }

    public function remove(): void
    {
        foreach ($this->context->getXdebugIniLinkingPathList() as $linkingPath) {
            if (file_exists($linkingPath)) {
                unlink($linkingPath);
            }
        }
        if (file_exists($this->context->getXdebugIniPath())) {
            unlink($this->context->getXdebugIniPath());
        }
    }

    public function set(string $key, string $value): void
    {
        $this->configManager->set(
            $this->getContext()->getXdebugIniPath(),
            $key,
            $this->quoteValue($key, $value)
        );
    }

    public function unSet(string $key): void
    {
        $this->configManager->unSet(
            $this->getContext()->getXdebugIniPath(),
            $key
        );
    }

    private function getContext(): Context
    {
        return $this->context;
    }

    private function quoteValue(string $key, string $value): string
    {
        return in_array($key, self::QUOTED_DIRECTIVE_LIST)
            ? '"' . $value . '"'
            : $value;
    }

    private function createIniConfig(
        string $host,
        string $xdebugOutputDir = '/tmp',
        string $xdebugIdeKey = 'PHPSTORM'
    ): array {

        return [
            'zend_extension' => 'xdebug.so',

            'xdebug.var_display_max_depth' => 5,
            'xdebug.var_display_max_data' => 250,
            'xdebug.var_display_max_children' => 5,
            'xdebug.max_nesting_level' => 250,
            'xdebug.cli_color' => 1,
            'xdebug.show_exception_trace' => 0,
            'xdebug.dump_globals' => 1,
            'xdebug.dump_once' => 1,
            'xdebug.show_local_vars' => 1,

            'xdebug.remote_autostart' => 1,           // If 0, CLI debug will not work. If 1, always try to start a remote debugging session and connect to a client, regardless of the GET/POST/COOKIE variable being present.
            'xdebug.remote_enable' => 1,              // trigger: cookie:XDEBUG_SESSION_START=${xdebugIdeKey}
            'xdebug.remote_host'=> "\"${host}\"",     // Host where the debug client is running, you can either use a host name, IP address, or 'unix:///path/to/sock' for a Unix domain socket. This setting is ignored if xdebug.remote_connect_back is enabled.
            'xdebug.remote_connect_back' => 0,        // If enabled, the xdebug.remote_host setting is ignored and Xdebug will try to connect to the client that made the HTTP request. This setting does not apply for debugging through the CLI
            'xdebug.remote_port' => 9000,
            'xdebug.remote_handler' => 'dbgp',
            'xdebug.remote_mode' => 'req',

            'xdebug.profiler_enable' => 0,
            'xdebug.profiler_enable_trigger' => 1,    // trigger: cookie:XDEBUG_PROFILE => 1
            'xdebug.profiler_output_dir' => "\"${xdebugOutputDir}\"",
            'xdebug.profiler_output_name' => '"%H.profiler"',

            'xdebug.auto_trace' => 0,
            'xdebug.trace_enable_trigger' => 1,       // trigger: cookie:XDEBUG_TRACE => 1
            'xdebug.trace_options' => 0,
            'xdebug.collect_params' => 4,
            'xdebug.collect_return' => 1,
            'xdebug.trace_format' => 2,
            'xdebug.trace_output_dir' => "\"${xdebugOutputDir}\"",
            'xdebug.trace_output_name' => '"%H.trace"',

            'xdebug.idekey' => "\"${xdebugIdeKey}\"",
        ];
    }
}
