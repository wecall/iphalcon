<?php

use Phalcon\Mvc\Controller;

class TestController extends Controller{

      // 常用方法
      public function indexAction(){
            // session_start();
            // header("content-type:image/png");    //设置创建图像的格式
            // $image_width=70;                      //设置图像宽度
            // $image_height=18;                     //设置图像高度
            // srand(microtime()*100000);          //设置随机数的种子
            // for($i=0;$i<4;$i++){                  //循环输出一个4位的随机数
            //    $new_number.=dechex(rand(0,15));
            // }
            // $_SESSION["check_checks"]=$new_number;    //将获取的随机数验证码写入到SESSION变量中     

            // $num_image=imagecreate($image_width,$image_height);  //创建一个画布
            // imagecolorallocate($num_image,255,50,255);     //设置画布的颜色
            // for($i=0;$i<strlen($_SESSION["check_checks"]);$i++){  //循环读取SESSION变量中的验证码
            //    $font=mt_rand(3,5);                              //设置随机的字体
            //    $x=mt_rand(1,8)+$image_width*$i/4;               //设置随机字符所在位置的X坐标
            //    $y=mt_rand(1,$image_height/4);                   //设置随机字符所在位置的Y坐标
            //    $color=imagecolorallocate($num_image,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));      //设置字符的颜色
            //    imagestring($num_image,$font,$x,$y,$_SESSION["check_checks"][$i],$color);      //水平输出字符
            // }
            // imagepng($num_image);       //生成PNG格式的图像
            // imagedestroy($num_image);   //释放图像资源
            // exit;
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

            // 邮件服务测试      ----------------------------  显示成功但是没有收到邮件问题
            $mail = new MailsService("qq.email");
            var_dump($mail->sendmail("416994628@163.com","测试邮件地址","<b>文本测试</b>"));
            exit;
      }

      public function codeAction(){
        exit(CaptchaService::build(6));
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
