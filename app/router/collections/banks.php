<?php

return call_user_func(function () {
    $c = new \Phalcon\Mvc\Micro\Collection();
    $c->setHandler('BanksController', true);
    $c->setPrefix('/bank');
    
    /**
     * 获取所有的银行信息 
     */
    $c->get('/', 'findAll');

    /**
     * 根据关键字搜索
     */
    $c->get('/search','search');
    
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
    $c->post('/{id:[0-9]+}','edit');

    /**
     * 删除记录
     */
    $c->post('/del/{id:[0-9]+}','delete');

    return $c;
});
