<?php

return call_user_func(function () {
    $c = new \Phalcon\Mvc\Micro\Collection();
    $c->setHandler('TasksController', true);
    $c->setPrefix('/task');
    // 
    $c->get('/task', 'index');

    return $c;
});
