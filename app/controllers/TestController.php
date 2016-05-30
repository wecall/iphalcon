<?php

use Phalcon\Mvc\Controller;

class TestController extends Controller{

    public function indexAction(){
       exit(phpinfo());
    }
}
