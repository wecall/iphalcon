<?php

/**
 * Created by IntelliJ IDEA.
 * User: tablee
 * Date: 9/14/15
 * Time: 1:20 下午
 */
class RestClientUtil
{
    public static function post($url, $params)
    {
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'timeout' => 1,
                'content' => json_encode($params)
            ]
        ];
        return self::httpRequest($url, $opts);
    }

    public static function get($url, $params)
    {
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'timeout' => 1,
                'content' => json_encode($params)
            ]
        ];
        return self::httpRequest($url, $opts);
    }

    public static function delete($url, $params)
    {
        $opts = [
            'http' => [
                'method' => 'DELETE',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'timeout' => 1,
                'content' => json_encode($params)
            ]
        ];
        return self::httpRequest($url, $opts);

    }

    private static function httpRequest($url, $opts)
    {
        $contents = stream_context_create($opts);
        $strData = file_get_contents($url, false, $contents);
        return json_decode($strData, true);
    }

} 