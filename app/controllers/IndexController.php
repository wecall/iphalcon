<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){
      
      // mongodb服务测试   ----------------------------------  成功
      // $mongodb = new  MongoService("imovie","qrcodes");
	    // var_dump($mongodb->count());
	    // exit;
      
      // 邮件服务测试      ----------------------------  显示成功但是没有收到邮件问题
      $mail = new MailsService("qq.email");
      var_dump($mail->send("416994628@163.com","测试邮件地址","<b>文本测试</b>"));
      exit;
    }


}
