<?php
//设置默认连接
$config['master'] = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'username' => 'dev',
    'password' => "ZD6&TF/2`jQX~2h`F',",
    'dbname' => 'test'
];

$config['slave'] = $config['master'];

return $config;