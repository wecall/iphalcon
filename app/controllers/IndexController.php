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
      echo $data;
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


      public function testAction(){
            // $url = "https://www.zhihu.com/";
            $url = "http://www.zhihu.com/people/dai-shu-qiong/about";
            $ch = curl_init();
            // 设置浏览器的特定header
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Host: www.zhihu.com",
              "Connection: keep-alive",
              "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
              "Upgrade-Insecure-Requests: 1",
              "DNT:1",
              "Accept-Language: zh-CN,zh;q=0.8,en-GB;q=0.6,en;q=0.4,en-US;q=0.2",
              'Cookie:d_c0="AIAAi_Rz-QmPTi0I_ivpxLCWD1C1KyuXeRs=|1464139326"; _za=6a4e0377-3a70-430e-8fd9-21a427eb3445; q_c1=10bc37a765244cdfb2f1189a6c3c5a77|1464139328000|1464139328000; l_cap_id="MjFlZGZhODBlMTNlNDE1Nzk1MDFiNTVlNDJkZmE4Yjg=|1465199185|354262a67255e87b552ddc113b24dc28f32cabdb"; cap_id="NTUwYTc5YTMwMmVkNDlhMmE2NDMyNmZjZjQ0MWZhZjU=|1465199185|8eb1fcf103e0bc54bb684f41df2a34d43fa5f172"; _zap=a2afe5a6-a55b-439d-9987-836f868cf3e1; login="NzJhNmM5NjRjZTllNGY0NWEwMjJkMzc3MzU1Yzc0ZDQ=|1465199194|ad689488ec6a24e27b38effe2021ff458e755bb2"; z_c0=Mi4wQUJETUJLdUtpUWdBZ0FDTDlIUDVDUmNBQUFCaEFsVk5XcmQ4VndCN3lLUm9ZcktMNDVuLTUtZElNWFRwd3VsdHd3|1465199194|e63d6010a81e23f4b93de286cc7980dac8f9858c; _xsrf=9c5e3e9576c417b83b71053cff18444c; a_t="2.0ABDMBKuKiQgXAAAAmdV8VwAQzASriokIAIAAi_Rz-QkXAAAAYQJVTVq3fFcAe8ikaGKyi-OZ_ufnSDF06cLpbcNfKSrsrtaeQFnO8oxXS8Vsv7cP6w=="; __utmt=1; __utma=51854390.2042775205.1465206941.1465206941.1465206941.1; __utmb=51854390.2.10.1465206941; __utmc=51854390; __utmz=51854390.1465206941.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=51854390.100-2|2=registration_date=20150813=1^3=entry_date=20150813=1',
            ));
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
            // 在HTTP请求头中"Referer: "的内容。
            curl_setopt($ch, CURLOPT_REFERER,"https://www.baidu.com/s?word=%E7%9F%A5%E4%B9%8E&tn=sitehao123&ie=utf-8&ssl_sample=normal&f=3&rsp=0");
            curl_setopt($ch, CURLOPT_ENCODING, "gzip, deflate, sdch");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT,120);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//302redirect
            // 针对https的设置
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $html = curl_exec($ch);
            curl_close($ch);
            if($html === false) {
              echo 'Curl error: ' . curl_error($ch) . "<br>\n\r";
            } else {
                  echo $html;
            }
      }


}
