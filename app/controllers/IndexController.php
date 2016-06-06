<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller{

    public function initialize(){
       
    }

    public function indexAction(){ 
      // 测试知乎数据
      $cookie = file_get_contents(BASE_PATH."/public/cookie/zhihu_cookies1.txt");
      $config = config("curl.zhihu");
      $curl = new RollingCurlService();
      $curl->set_cookie($cookie);
      $curl->set_gzip(true);
      $url = "http://www.zhihu.com/people/dai-shu-qiong/about";
      $curl->get($url,array(),$config["header"],$config["options"]);
      $data = $curl->execute();
      var_dump($data);
      file_put_contents(BASE_PATH."/public/cookie/zhihu_data.html",$data);
      exit;
      // RollingCURL 服务测试
      // $cookie = file_get_contents(BASE_PATH."/public/cookie/zhihu_cookies.txt");

      // $curl = new RollingCurlService();

      // $curl->set_cookie($cookie);
      // $curl->set_gzip(true);
      // // $url = "http://www.zhihu.com/people/dai-shu-qiong/about";
      // $url = "http://www.wecall.me/";
      // $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
      // $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
      // $header[] = "Cache-Control: max-age=0"; 
      // $header[] = "Connection: keep-alive"; 
      // $header[] = "Keep-Alive: 300"; 
      // $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
      // $header[] = "Accept-Language: en-us,en;q=0.5"; 
      // $header[] = "Accept-Encoding: gzip";
      // $header[] = "Pragma: "; // browsers keep this blank. 

      // $curl->get($url,array(),$header);
      // $curl->callback = function($response, $info, $request, $error) {
      //   var_dump($response);
      //   echo "-------------<br/>";
      //   var_dump($info);
      //   echo "-------------<br/>";
      //   var_dump($request);
      //   echo "-------------<br/>";
      //   var_dump($error);
      //   echo "-------------<br/>";
      // };
      // $data = $curl->execute();
      // file_put_contents(BASE_PATH."/public/cookie/zhihu_data.html",$data);
      // exit;
      // mongodb服务测试   ----------------------------------  成功
      // $mongodb = new  MongoService("imovie","qrcodes");
	    // var_dump($mongodb->count());
	    // exit;
      
      // 邮件服务测试      ----------------------------  显示成功但是没有收到邮件问题
      // $mail = new MailsService("qq.email");
      // var_dump($mail->send("416994628@163.com","测试邮件地址","<b>文本测试</b>"));
      exit;
    }


}
