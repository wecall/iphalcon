<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller{

	/**
	 * 身份验证
	 */
    protected $access_token;

    public function onConstruct(){

      $this->access_token = \Tools::buildToken();
      
      $accessToken = $this->request->getHeader(GlobalConsts::AUTH_TOKEN);
      
    }

    

}