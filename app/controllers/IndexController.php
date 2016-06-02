<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){
       
       $mail = new MailsService();
       $mail->send();
       exit;
    }
}
