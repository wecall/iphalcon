<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){ 
    	$xhprofService = new XhprofService();
    	$xhprofService->beginDebug();
    	$this->testXhprof();
    	$xhprofService->endDebug();
    }


    public function testXhprof(){
    	$arr = ["123","456","582","251"];
    	sort($arr);
    }

}
