<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Hgraca\XdebugManager\UI\Console\InstallCommand;
use Hgraca\XdebugManager\UI\Console\OffCommand;
use Hgraca\XdebugManager\UI\Console\OnCommand;
use Hgraca\XdebugManager\UI\Console\RenameProjectCommand;
use Hgraca\XdebugManager\UI\Console\SetCommand;
use Hgraca\XdebugManager\UI\Console\UnInstallCommand;

define('ROOT_DIR', __DIR__ . '/..');

$console = new \Cilex\Application('XdebugManager');

$console->command(new InstallCommand());
$console->command(new UnInstallCommand());
$console->command(new OnCommand());
$console->command(new OffCommand());
$console->command(new SetCommand());
$console->command(new RenameProjectCommand());

return $console;
