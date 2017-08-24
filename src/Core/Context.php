<?php

namespace Hgraca\XdebugManager\Core;

use Hgraca\XdebugManager\Infrastructure\PackageManager\AptGet;
use Hgraca\XdebugManager\Infrastructure\PackageManager\PackageManagerInterface;
use Hgraca\XdebugManager\Infrastructure\PackageManager\Pecl;

final class Context
{
    const PHP_VERSION_ANY = '*';
    const PHP_VERSION_7_1 = '7.1';
    const PHP_VERSION_7_0 = '7.0';

    private const CONTEXT_LIST = [
        [
            'assert' => '/etc/php/7.1',
            'xdebug.ini' => "/etc/php/7.1/mods-available/xdebug.ini",
            'link_to' => [
                '/etc/php/7.1/cli/conf.d/20-xdebug.ini',
                '/etc/php/7.1/fpm/conf.d/20-xdebug.ini',
            ],
            'package_manager' => AptGet::class,
            'version' => self::PHP_VERSION_7_1,
            'BASHRC' => '/etc/bash.bashrc',
        ],
        [
            'assert' => '/etc/php/7.0',
            'xdebug.ini' => "/etc/php/7.0/mods-available/xdebug.ini",
            'link_to' => [
                '/etc/php/7.0/cli/conf.d/20-xdebug.ini',
                '/etc/php/7.0/fpm/conf.d/20-xdebug.ini',
            ],
            'package_manager' => AptGet::class,
            'version' => self::PHP_VERSION_7_0,
            'BASHRC' => '/etc/bash.bashrc',
        ],
        [
            'assert' => '/usr/local/etc/php/php.ini',
            'xdebug.ini' => "/usr/local/etc/php/conf.d/xdebug.ini",
            'link_to' => [],
            'package_manager' => Pecl::class,
            'version' => self::PHP_VERSION_ANY,
            'BASHRC' => '/etc/bash.bashrc',
        ],
    ];

    /**
     * @var PackageManagerInterface
     */
    private $packageManager;

    /**
     * @var array
     */
    private $context;

    public function __construct() {
        $this->context = $this->findContext();

        $this->packageManager = $this->constructPackageManager();
    }

    public function getXdebugIniPath(): string
    {
        return $this->context['xdebug.ini'];
    }

    public function getXdebugIniLinkingPathList(): array
    {
        return $this->context['link_to'];
    }

    public function getPackageManager(): PackageManagerInterface
    {
        return $this->packageManager;
    }

    public function getBashrcPath(): string
    {
        return $this->context['BASHRC'];
    }

    private function constructPackageManager(): PackageManagerInterface
    {
        return new $this->context['package_manager'];
    }

    /**
     * @throws PhpIniFileNotFoundException
     */
    private function findContext(): array
    {
        foreach (self::CONTEXT_LIST as $context) {
            if (file_exists($context['assert'])) {
                return $context;
            }
        }

        throw new PhpIniFileNotFoundException();
    }
}
