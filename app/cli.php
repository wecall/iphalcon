<?php

require_once APP_PATH . '/init.php';

use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;

Class Cli extends init{
	/**
     * @var \Phalcon\Di\FactoryDefault
     */
    public $di;

    public function __construct()
    {
        $this->di = new CliDI();
    }

    public function run()
    {
        $this->registerDirs();
        $this->loadRedis();
        $this->loadDb();
        return $this->di;
    }
}

$cli = new Cli();
$di = $cli->run();

$console = new ConsoleApp();
$console->setDI($di);

$arguments = $params = [];

foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $params[] = $arg;
    }
}
if (count($params) > 0) {
    $arguments['params'] = $params;
}


// define global constants for the current task and action
define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    // handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}
