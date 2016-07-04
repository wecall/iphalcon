<?php

class SecurityUtil
{
    const FLAG_NUMERIC = 1;
    const FLAG_NO_NUMERIC = 2;
    const FLAG_ALPHANUMERIC = 3;
    const FLAG_SECURITY_KEY = "wecall.me";

    /**
     * 生成随机密码
     *
     * @param integer $length Desired length (optional)
     * @param string  $flag   Output type (NUMERIC, ALPHANUMERIC, NO_NUMERIC)
     *
     * @return string Password
     */
    public static function passwdGen($length = 8, $flag = self::FLAG_NO_NUMERIC) {
        switch ($flag)
        {
            case self::FLAG_NUMERIC:
                $str = '0123456789';
                break;
            case self::FLAG_NO_NUMERIC:
                $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case self::FLAG_ALPHANUMERIC:
            default:
                $str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        for ($i = 0, $passwd = ''; $i < $length; $i++)
            $passwd .= substr($str, mt_rand(0, strlen($str) - 1), 1);

        return $passwd;
    }

    public static function hash($str)
    {
        return md5($str . self::FLAG_SECURITY_KEY);
    }

    /**
     * 设置文件保存的名字
     */
    public static function saveImageName(){
        $number  = self::passwdGen(6,self::FLAG_NUMERIC);
        $nowtime = time();
        return md5($number.$nowtime.self::FLAG_SECURITY_KEY);
    }

    /**
     * 设置 Token
     */
    public static function setAuthToken(){
    	$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ0123456789ABCDEFGHJKMNPQRSTUVWXYZ0123456789ABCDEFGHJKMNPQRSTUVWXYZ0123456789';
        $chars = str_shuffle($chars);
        return sha1(substr($chars,0,32));
    }

    /**
     * 读取 Token
     */
    public static function getAuthToken($authToken)
    {
        return getDI('crypt')->encryptBase64(json_encode($authToken));
    }
}