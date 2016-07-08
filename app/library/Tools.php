<?php


/**
 * 常用的字符操作，日期操作 ULR操作等等 操作类
 * 
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */

class Tools {


	
	/**
	 * 生成用户访问的token
	 */
	public static function buildToken(){
		$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ0123456789ABCDEFGHJKMNPQRSTUVWXYZ0123456789ABCDEFGHJKMNPQRSTUVWXYZ0123456789';
        $chars = str_shuffle($chars);

        return sha1(substr($chars,0,32));
	}

	/**
	 * 将 css 中style 转变为hash 键值对
	 * @param "font-size:1.6em;color:red;font-face:文泉驿正黑;"
	 */
	public static function strStyleToHash($style){
		$styleArr = array();
		foreach (explode(";", $style) as $value) {
		    if (empty($value)) {
		        break;
		    }
		    $item = explode(":", $value);
		    $styleArr[$item[0]] =  $item[1];
		}
		
		return $styleArr;
	}

	/**
	 * 清除字符串中的html标签字符
	 */
	public static function clearHtml($descclear){
		$descclear = str_replace("\r","",$descclear);//过滤换行
		$descclear = str_replace("\n","",$descclear);//过滤换行
		$descclear = str_replace("\t","",$descclear);//过滤换行
		$descclear = str_replace("\r\n","",$descclear);//过滤换行
		$descclear = preg_replace("/\s+/", " ", $descclear);//过滤多余回车
		$descclear = preg_replace("/<[ ]+/si","<",$descclear); //过滤<__("<"号后面带空格)
		$descclear = preg_replace("/<\!--.*?-->/si","",$descclear); //过滤html注释
		$descclear = preg_replace("/<(\!.*?)>/si","",$descclear); //过滤DOCTYPE
		$descclear = preg_replace("/<(\/?html.*?)>/si","",$descclear); //过滤html标签
		$descclear = preg_replace("/<(\/?head.*?)>/si","",$descclear); //过滤head标签
		$descclear = preg_replace("/<(\/?meta.*?)>/si","",$descclear); //过滤meta标签
		$descclear = preg_replace("/<(\/?body.*?)>/si","",$descclear); //过滤body标签
		$descclear = preg_replace("/<(\/?link.*?)>/si","",$descclear); //过滤link标签
		$descclear = preg_replace("/<(\/?form.*?)>/si","",$descclear); //过滤form标签
		$descclear = preg_replace("/cookie/si","COOKIE",$descclear); //过滤COOKIE标签
		$descclear = preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si","",$descclear); //过滤applet标签
		$descclear = preg_replace("/<(\/?applet.*?)>/si","",$descclear); //过滤applet标签
		$descclear = preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si","",$descclear); //过滤style标签
		$descclear = preg_replace("/<(\/?style.*?)>/si","",$descclear); //过滤style标签
		$descclear = preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si","",$descclear); //过滤title标签
		$descclear = preg_replace("/<(\/?title.*?)>/si","",$descclear); //过滤title标签
		$descclear = preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si","",$descclear); //过滤object标签
		$descclear = preg_replace("/<(\/?objec.*?)>/si","",$descclear); //过滤object标签
		$descclear = preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si","",$descclear); //过滤noframes标签
		$descclear = preg_replace("/<(\/?noframes.*?)>/si","",$descclear); //过滤noframes标签
		$descclear = preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si","",$descclear); //过滤frame标签
		$descclear = preg_replace("/<(\/?i?frame.*?)>/si","",$descclear); //过滤frame标签
		$descclear = preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si","",$descclear); //过滤script标签
		$descclear = preg_replace("/<(\/?script.*?)>/si","",$descclear); //过滤script标签
		$descclear = preg_replace("/javascript/si","Javascript",$descclear); //过滤script标签
		$descclear = preg_replace("/vbscript/si","Vbscript",$descclear); //过滤script标签
		$descclear = preg_replace("/on([a-z]+)\s*=/si","On\\1=",$descclear); //过滤script标签
		$descclear = preg_replace("/&#/si","&＃",$descclear); //过滤script标签，如javAsCript:alert();
		//使用正则替换
		$pat = "/<(\/?)(script|i?frame|style|html|body|li|i|map|title|img|link|span|u|font|table|tr|b|marquee|td|strong|div|a|meta|\?|\%)([^>]*?)>/isU";
		
		return preg_replace($pat,"",$descclear);
	}

	/**
	 * 获取文件扩展名
	 *
	 * @param $file
	 *
	 * @return mixed|string
	 */
	public static function getFileExtension($file) {
		if (is_uploaded_file($file))
		{
			return "unknown";
		}

		return pathinfo($file, PATHINFO_EXTENSION);
	}

	/**
	 * 将对象转化为数组
	 * @param  [object] $obj [对象]
	 * @return [array]      [数组]
	 */
	public static function object_to_array($obj) 
	{ 
	    $_arr= is_object($obj) ? get_object_vars($obj) : $obj; 
	    foreach($_arr as $key=> $val) 
	    { 
	        $val= (is_array($val) || is_object($val)) ? object_to_array($val) : $val; 
	        $arr[$key] = $val; 
	    } 
	    return $arr; 
	}


	/**
	 * @param        $url
	 * @param string $method
	 * @param null   $postFields
	 * @param null   $header
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public static function curl($url, $method = 'GET', $postFields = null, $header = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https")
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		switch ($method)
		{
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);

				if (!empty($postFields))
				{
					if (is_array($postFields) || is_object($postFields))
					{
						if (is_object($postFields))
							$postFields = Tools::object2array($postFields);
						$postBodyString = "";
						$postMultipart = false;
						foreach ($postFields as $k => $v)
						{
							if ("@" != substr($v, 0, 1))
							{ //判断是不是文件上传
								$postBodyString .= "$k=" . urlencode($v) . "&";
							}
							else
							{ //文件上传用multipart/form-data，否则用www-form-urlencoded
								$postMultipart = true;
								$postFields[$k] = curl_file_create(substr($v, 1, strlen($v)));
							}
						}
						unset($k, $v);
						if ($postMultipart)
						{
							curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
						}
						else
						{
							curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
						}
					}
					else
					{
						curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
					}

				}
				break;
			default:
				if (!empty($postFields) && is_array($postFields))
					$url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($postFields);
				break;
		}
		curl_setopt($ch, CURLOPT_URL, $url);

		if (!empty($header) && is_array($header))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$response = curl_exec($ch);
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch), 0);
		}
		curl_close($ch);

		return $response;
	}
}