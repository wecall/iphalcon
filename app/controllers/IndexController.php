<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){ 

      // 测试
      $GtSdk = new GeetestService();
      
      $user_id = "test";
      $status = $GtSdk->pre_process($user_id);

      $this->session->set("gtserver", $status);
      $this->session->set("user_id", $user_id);
      echo $GtSdk->get_response_str();

      // $user_id = $_SESSION['user_id'];
      // if ($_SESSION['gtserver'] == 1) {
      //     $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
      //     if ($result) {
      //         echo 'Yes!';
      //     } else{
      //         echo 'No';
      //     }
      // }else{
      //     if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
      //         echo "yes";
      //     }else{
      //         echo "no";
      //     }
      // }
      exit();
    }


}
