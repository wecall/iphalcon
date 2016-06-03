<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){
       $mail = new MailsService();
       var_dump($mail->send("416994628@qq.com","测试邮件地址","<b>文本测试</b>"));
       exit;
    }
}
