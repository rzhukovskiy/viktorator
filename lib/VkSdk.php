<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:24
 */
class VkSdk
{
    private static $client_secret  = 'eH3T0i8mYSmcIoHqGppB';
    private static $urlAuth        = 'http://oauth.vk.com/authorize';
    private static $config         = [
        'client_id'     => '6253298',
        'redirect_uri'  => 'http://mediastog.ru/site/auth',
        'response_type' => 'code',
        'scope'         => 'offline'
    ];

    public static function getAuthUrl()
    {
        return self::$urlAuth . '?' . urldecode(http_build_query(self::$config));
    }
}