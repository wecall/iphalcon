<?php
/**
 * @name Tools 工具类
 * @desc 常用的字符操作，日期操作 ULR操作等等
 * @author ron chen
 * @date 2014-12-01
*/
class Tools {
	
	public static function cleanNonUnicodeSupport($pattern) {
		if (!defined('PREG_BAD_UTF8_OFFSET'))
			return $pattern;

		return preg_replace('/\\\[px]\{[a-z]\}{1,2}|(\/[a-z]*)u([a-z]*)$/i', "$1$2", $pattern);
	}
}