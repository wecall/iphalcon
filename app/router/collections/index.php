<?php

return call_user_func(function () {
    $c = new \Phalcon\Mvc\Micro\Collection();
    $c->setHandler('IndexController', true);
    $c->setPrefix('/index');
    // 
    $c->get('/', 'index');

    return $c;
});
