<?php

use Phalcon\Mvc\Controller;

class TasksController extends Controller{

    public function initialize(){
       
    }
    
    public function indexAction(){ 
        $arr = ["123","456","582","251"];
    	exit(json_encode($arr));
    }
}
