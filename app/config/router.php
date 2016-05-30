<?php

$config['default'] = array(
	'path'=>array(
        "controller" => "index",
        "action"     => "index",
    )
);
$config['index'] = array(
    'mapping'=>'\/?([a-zA-Z0-9_-]*)\/?([a-zA-Z0-9_]*)\/?(.*)',
    'path'=>array(
        "controller" => 1,
        "action"     => 2,
        'params'     => 3
    )
);
/* 404 */
$config['notfound'] = array(
    'path'=>array(
        'controller' => 'http',
        'action'     => 'err404'
    )
);

return $config;