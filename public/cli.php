<?php
define('VERSION', '1.0.0');

date_default_timezone_set('Asia/Shanghai');

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);

define('BASE_PATH', realpath(dirname(__FILE__)) . '/..');

define('APP_PATH', BASE_PATH . '/app');

define('LOGS_PATH', APP_PATH . '/logs');

define('CONFIG_PATH', APP_PATH . '/config');
// 定义资源文件的访问地址
define('PUBLIC_PATH', BASE_PATH . '/public/');

define('CACHE_PATH', BASE_PATH . '/public/cache/');
// 定义远程获取图片的存储路径
define('IMG_PATH', BASE_PATH . '/public/upload/original/');

define('CACHE_TIME', 0);

require_once APP_PATH . "/cli.php";