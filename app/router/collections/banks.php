<?php

return call_user_func(function () {
    $c = new \Phalcon\Mvc\Micro\Collection();
    $c->setHandler('BanksController', true);
    $c->setPrefix('/bank');
    /**
     * 获取所有的银行信息 
     */
    $c->get('/', 'index');

    /**
     * 根据关键字搜索
     */
    $c->get('/search/{name}','search');
    
    /**
     * 根据主键查询
     */
    $c->get('/{id:[0-9]+}','findById');

    /**
     * 添加银行记录
     */
    $c->post('/','add');

    /**
     * 更新记录
     */
    $c->put('/{id:[0-9]+}','update');

    /**
     * 删除记录
     */
    $c->delete('/{id:[0-9]+}','delete');

    return $c;
});
