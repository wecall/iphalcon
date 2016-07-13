<?php
//设置默认连接
// $config['master'] = [
//     'host' => '127.0.0.1',
//     'port' => '3306',
//     'username' => 'dev',
//     'password' => "ZD6&TF/2`jQX~2h`F',",
//     'dbname' => 'test'
// ];

// $config['master'] = [
//     'host' => '172.20.10.247',
//     'port' => '3306',
//     'username' => 'dev',
//     'password' => "ZD6&TF/2`jQX~2h`F',",
//     'dbname' => 'chat_db'
// ];


// $config['slave'] = $config['master'];

// $config['db_master'] = [
// 	'adapter' => 'Mysql',
//     'host' => '172.20.10.247',
//     'port' => '3306',
//     'username' => 'dev',
//     'password' => "ZD6&TF/2`jQX~2h`F',",
//     'dbname' => 'chat_db'
// ];

// $config['db_slave'] = $config['db_master'];
// $config['db_event'] = [
//     'host' => '182.92.99.40',
//     'port' => '3306',
//     'username' => 'admin',
//     'password' => "ronchen",
//     'dbname' => 'admin'
// ];

$config['db_master'] = [
	'adapter' => 'Mysql',
    'host' => '182.92.99.40',
    'port' => '3306',
    'username' => 'admin',
    'password' => "ronchen",
    'dbname' => 'admin',
    'charset' => 'utf8'
];

$config['db_slave'] = $config['db_master'];

return $config;