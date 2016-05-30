<?php

class SecurityUtil
{
    public static function hash($str)
    {
        return md5($str . "ybzf");
    }

    public static function getAuthToken($user_id, $site_id, $pharmacist_id, $store_id, $store_user_id, $phone, $device)
    {
        $authToken = new \Dto\AuthToken();
        $authToken->phone = $phone;
        $authToken->pharmacistId = $pharmacist_id;
        $authToken->storeId = $store_id;
        $authToken->storeUserId = $store_user_id;
        $authToken->userId = $user_id;
        $authToken->siteId = $site_id;
        $authToken->time = time();
        $authToken->setDevice($device);
        $authToken->ackCode = $authToken->generateAckCode();
        return getDI('crypt')->encryptBase64(json_encode($authToken));
    }
}