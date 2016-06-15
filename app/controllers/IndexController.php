<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){ 

      // 测试
      $GtSdk = new GeetestService();
      session_start();
      $user_id = "test";
      $status = $GtSdk->pre_process($user_id);
      $_SESSION['gtserver'] = $status;
      $_SESSION['user_id'] = $user_id;
      echo $GtSdk->get_response_str();
      exit();
    }


}
