<?php

use Phalcon\Mvc\Controller;

class TestController extends Controller{

      // 常用方法
      public function indexAction(){
            // 测试 Fetcher
            // $fetcher = new Fetcher();
            // $content = $fetcher->get("http://weixin.test.51jk.com/");
            // echo $content;
            // exit;

            // 测试 worker 
            // $w = new Worker();
            // $w->count = 8;
            // $w->is_once = true;
            // // 每个进程循环多少次
            // $count = 100;        
            // $w->on_worker_start = function($worker) use ($count) {
            //     //$progress_id = posix_getpid();

            //     for ($i = 0; $i < $count; $i++) 
            //     {
            //       // 处理业务
            //       // ...
            //     }
            // }; 
            // $w->run();
            // exit;

            // // 测试Redis 服务
            // $redisObj = new RedisService($this->redis);
            // $redisObj->set("admin","admin",120);
            // echo  $redisObj->get("admin");
            // exit;

            // 测试知乎数据
            // $cookie = file_get_contents(BASE_PATH."/public/cookie/zhihu_cookies1.txt");
            // try {
            //   // 保证配置文件没有错误
            //   $config = config('curl.zhihu');
            // } catch (Exception $e) {
            //   echo $e->getMessage();
            // }
            // $curl = new RollingCurlService();
            // $curl->set_cookie($cookie);
            // $curl->set_gzip(true);
            // $url = "http://www.zhihu.com/people/dai-shu-qiong/about";
            // $curl->get($url,array(),$config["header"],$config["options"]);
            // $data = $curl->execute();
            // echo $data;
            // file_put_contents(BASE_PATH."/public/cookie/zhihu_data.html",$data);
            // exit;

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

            // // 邮件服务测试      ----------------------------  显示成功但是没有收到邮件问题
            // $mail = new MailsService("qq.email");
            // var_dump($mail->sendmail("416994628@163.com","测试邮件地址","<b>文本测试</b>"));
            // exit;
      }

      // 极验验证码登录
      public function verifyAction(){
            // 测试
            $GtSdk = new GeetestService();

            $user_id = "test";
            $status = $GtSdk->pre_process($user_id);

            $this->session->set("gtserver", $status);
            $this->session->set("user_id", $user_id);
            echo $GtSdk->get_response_str();
      }

      public function checkAction(){
            $GtSdk = new GeetestService();
            $user_id = $this->session->get("user_id");
            if ($this->session->get("gtserver") == 1) {
                $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
                if ($result) {
                    echo 'Yes!';
                } else{
                    echo 'No';
                }
            }else{
                if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                    echo "yes";
                }else{
                    echo "no";
                }
            }
      }
}
