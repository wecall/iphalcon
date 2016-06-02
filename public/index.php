<?php
date_default_timezone_set('Asia/Shanghai');

ini_set('html_errors', false);

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);

define('BASE_PATH', realpath(dirname(__FILE__)) . '/..');

define('APP_PATH', BASE_PATH . '/app');

define('LOGS_PATH', APP_PATH . '/logs');

define('CONFIG_PATH', APP_PATH . '/config');

define('CACHE_PATH', BASE_PATH . '/public/cache/');

define('CACHE_TIME', 0);

require_once APP_PATH . '/app.php';
