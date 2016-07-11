<?php

use Phalcon\Mvc\Controller;

class BanksController extends BaseController{

    /**
     * 获取所有的银行信息 
     */
    public function index(){ 
		$params = $this->request->getQuery();
		var_dump($params);
    }

    /**
     * 根据关键字搜索 
     */
    public function search(){ 
    	
    }


    /**
     * 根据主键查询 
     */
    public function findById(){ 
    	
    }

    public function add(){
		$data = $this->request->getJsonRawBody(true);
		var_dump($data);
		exit;
    }

    public function update(){

    }


    public function delete(){

    }
}
