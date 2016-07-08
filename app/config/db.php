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

$config['chat_db_master'] = [
	'adapter' => 'Mysql',
    'host' => '172.20.10.247',
    'port' => '3306',
    'username' => 'dev',
    'password' => "ZD6&TF/2`jQX~2h`F',",
    'dbname' => 'chat_db'
];

$config['chat_db_slave'] = $config['chat_db_master'];

return $config;