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
}