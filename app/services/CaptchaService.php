<?php
/**
 * 图片验证码 操作类
 *
 *
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */
class CaptchaService{

	private static $seKey;

	private static function words($len) {
		$charecters = "123456789abcdefghijkmnpqrstuvwxyz";
		$max = strlen($charecters) - 1;
		$keyword = array();
		for ($i = 0; $i < $len; $i++)
		{
			array_push($keyword, $charecters[rand(0, $max)]);
		}
		// 设置Session
		session_start();
		$_SESSION[self::$seKey] = strtolower(implode("",$keyword));

		return $keyword;
	}

	/**
	 * 生成验证码字符串，写入SESSION，将字符串图片返回给浏览器
	 *
	 * @param int $len
	 * @param int $width
	 * @param int $height
	 * @param int $font_size
	 */
	public static function build($len, $width = 108, $height = 30, $font_size = 18) {
		$image = ImageManager::createWhiteImage($width, $height);

		$fontname  = sprintf("captcha%d.ttf",mt_rand(0,5));
		$font_path = realpath(PUBLIC_PATH.'fonts/captcha/'.$fontname);
		
		$color = imagecolorallocate($image, mt_rand(0, 100), mt_rand(20, 120), mt_rand(50, 150));

		// 绘制干扰线
		self::drawCurve($image,$width,$height,$color,$font_size);
		
		// 绘验证码  
		$keyword = self::words($len);
		$codeNX = 0;  // 验证码第N个字符的左边距  
        for ($i = 0; $i < $len; $i++) {  
            $codeNX += mt_rand($font_size * 1.2, $font_size * 1.6);
           	// 写一个验证码字符
            imagettftext($image, $font_size, mt_rand(-40, 70), $codeNX, $font_size * 1.5, $color, $font_path, $keyword[$i]);  
        }  

		header('Content-Type: image/png');
		header("Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate");
		header('Cache-Control: post-check=0, pre-check=0', false);  
		header("Pragma: no-cache");

		ImagePng($image);
		ImageDestroy($image);
	}

	/**
	 * 绘制干扰线
	 *@param $width 图片宽度
	 *@param $height 图片长度
	 */
	private function drawCurve($image,$width = 108, $height = 30,$color,$font_size){
		$A = mt_rand(1, $height / 2);             // 振幅  
        $b = mt_rand(-$height / 4, $height / 4);   	// Y轴方向偏移量  
        $f = mt_rand(-$height / 4, $height / 4);    // X轴方向偏移量  
        $T = mt_rand($height * 1.5, $width * 2);    // 周期  
        $w = (2 * M_PI) / $T;  

        $px1 = 0;  // 曲线横坐标起始位置  
        $px2 = mt_rand($width / 2, $width * 0.667);  // 曲线横坐标结束位置             
        for ($px=$px1; $px <= $px2; $px= $px + 0.9) {  
            if ($w!=0) {  
                $py = $A * sin($w*$px + $f)+ $b + $height/2;  // y = Asin(ωx+φ) + b  
                $i  = (int) (($font_size - 6)/4);  
                while ($i > 0) {   
                    imagesetpixel($image, $px + $i, $py + $i, $color);  // 这里画像素点比imagettftext和imagestring性能要好很多                    
                    $i--;  
                }  
            }  
        }  
          
        $A = mt_rand(1, $height / 2);                  // 振幅          
        $f = mt_rand(-$height / 4, $height / 4);   // X轴方向偏移量  
        $T = mt_rand($height * 1.5, $width * 2);  // 周期  
        $w = (2* M_PI) / $T;        
        $b = $py - $A * sin($w * $px + $f) - $height / 2;  
        $px1 = $px2;  
        $px2 = $width;  
        for ($px=$px1; $px<=$px2; $px=$px+ 0.9) {  
            if ($w!=0) {  
                $py = $A * sin($w * $px + $f)+ $b + $height / 2;  // y = Asin(ωx+φ) + b  
                $i = (int) (($font_size - 8) / 4);  
                while ($i > 0) {           
                    imagesetpixel($image, $px + $i, $py + $i, $color);  // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多      
                    $i--;  
                }  
            }
        } 
	}

	/** 
     * 验证验证码是否正确 
     * 
     * @param string $code 用户验证码 
     * @return bool 用户验证码是否正确 
     */  
    public static function check($code) {  
        isset($_SESSION) || session_start();  
        // 验证码不能为空  
        if(emptyempty($code) || emptyempty($_SESSION[self::$seKey])) {  
            return false;  
        }  
        // session 过期  
        if(time() - $_SESSION[self::$seKey]['time'] > self::$expire) {  
            unset($_SESSION[self::$seKey]);  
            return false;  
        }  
  
        if($code == $_SESSION[self::$seKey]['code']) {  
            return true;  
        }  
  
        return false;  
    }

	/**
	 * 验证是否是合法的验证码
	 *
	 * @param  String   $captcha
	 * @param  int 		$size
	 * @return int
	 */
	public static function isCaptcha($captcha, $size = 4) {
		return (bool)preg_match('/^[123456789abcdefghijkmnpqrstuvwxyz]{' . $size . '}$/ui', $captcha);
	}
}