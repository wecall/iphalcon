<?php

use Phalcon\Mvc\Controller;

class HttpController extends Controller{

	/**
	 * 404 
	 */
    public function err404Action(){
       exit("404");
    }
}
