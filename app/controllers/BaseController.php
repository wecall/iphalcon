<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller{

	/**
	 * 身份验证
	 */
    protected $access_token;

    public function onConstruct(){
       $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ0123456789ABCDEFGHJKMNPQRSTUVWXYZ0123456789';
       $chars = str_shuffle($chars);

       $this->access_token = sha1(substr($chars,0,32));

       $accessToken = $this->request->getHeader(GlobalConsts::AUTH_TOKEN);
       
       

    }

    

}