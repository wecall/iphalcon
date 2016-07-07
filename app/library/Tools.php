<?php


/**
 * 常用的字符操作，日期操作 ULR操作等等 操作类
 * 
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */

class Tools {
	
	public static function cleanNonUnicodeSupport($pattern) {
		if (!defined('PREG_BAD_UTF8_OFFSET'))
			return $pattern;

		return preg_replace('/\\\[px]\{[a-z]\}{1,2}|(\/[a-z]*)u([a-z]*)$/i', "$1$2", $pattern);
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
}