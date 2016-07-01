<?php

use Phalcon\Mvc\Controller;

class TasksController extends BaseController{

    
    public function index(){ 
    	$arr = ["1","3","2","1"];
    	exit(json_encode($arr));
    }
}
