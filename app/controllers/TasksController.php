<?php

use Phalcon\Mvc\Controller;

class TasksController extends BaseController{

    
    public function index(){ 
        $arr = ["123","456","582","251"];
    	exit(json_encode($arr));
    }
}
