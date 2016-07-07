<?php
/**
 * 日志 操作类
 *
 *
 * @author ron_chen<ron_chen@hotmail.com>
 * @copyright ron_chen<ron_chen@hotmail.com>
 * @link http://www.ronchen.me/
 */

namespace services;

class LogsService{

    private static function setBasePath(){
         SeasLog::setBasePath(LOGS_PATH);
    }

    /**
     * 设置各种级别的日志
     */
    public static function write($message="",$params = array(),$module="default",$level=SEASLOG_INFO){
        self::setBasePath();
        SeasLog::log($level,$message,$params,$module);
    }

    /**
     * debug
     */
    public static function debug($message="",$params=array(),$module="default"){
        self::setBasePath();
        SeasLog::debug($message,$params,$module);
    }

    /**
     * info
     */
    public static function info($message="",$params=array(),$module="default"){
        self::setBasePath();
        SeasLog::info($message,$params,$module);
    }

    /**
     * notice
     */
    public static function notice($message="",$params=array(),$module="default"){
        self::setBasePath();
        SeasLog::notice($message,$params,$module);
    }

    /**
     * warning
     */
    public static function warning($message="",$params=array(),$module="default"){
        self::setBasePath();
        SeasLog::warning($message,$params,$module);
    }

    /**
     * error
     */
    public static function error($message="",$params=array(),$module="default"){
        self::setBasePath();
        SeasLog::error($message,$params,$module);
    }
}