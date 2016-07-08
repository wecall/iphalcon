<?php

return call_user_func(function () {
    $c = new \Phalcon\Mvc\Micro\Collection();
    $c->setHandler('WechatController', true);
    $c->setPrefix('/wechat');
    // 
    $c->get('/', 'index');

    return $c;
});
