<?php

return call_user_func(function () {
    $c = new \Phalcon\Mvc\Micro\Collection();
    $c->setHandler('TestController', true);
    $c->setPrefix('/test');
    // 
    $c->get('/', 'index');

    return $c;
});
