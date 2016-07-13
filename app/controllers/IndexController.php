<?php

use Phalcon\Mvc\Controller;

use business\website\BDianping as BDianping;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function index(){ 
    	
        $dp = new BDianping();
        $dp->getLanguageBarJson("password");
        return ["2"];
    }


    public function testXhprof(){
    	$arr = ["1","3","2","1"];
    	sort($arr);
    }

}
