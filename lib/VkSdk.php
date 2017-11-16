<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:24
 */
class VkSdk
{
    const API_URL       = 'https://api.vk.com/method/';
    const API_VERSION   = '5.59';

    private static function callApi($method, $params)
    {
        $url = self::API_URL . $method . '?' . urldecode(http_build_query($params)) . 'v=' . self::API_VERSION;
        $data = json_decode(file_get_contents($url), true);

        if (empty($infoUser['error'])) {
            return $data;
        } else {
            return false;
        }
    }

    public static function getAuthUrl()
    {
        $params = [
            'client_id'     => Globals::$config->app_id,
            'redirect_uri'  => Globals::$config->redirect_uri,
            'response_type' => 'code',
            'scope'         => 'offline,wall,notify,friends'
        ];
        return 'http://oauth.vk.com/authorize?' . urldecode(http_build_query($params));
    }

    /**
     * @param $code string
     * @return array|bool
     */
    public static function getTokenByCode($code)
    {
        $params = array(
            'client_id'     => Globals::$config->app_id,
            'client_secret' => Globals::$config->app_secret,
            'redirect_uri'  => Globals::$config->redirect_uri,
            'code'          => $code,
        );

        $infoToken = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        if (empty($infoToken['error'])) {
            return $infoToken;            
        } else {
            return false;
        }
    }

    public static function getUserInfoByToken($user_id, $token)
    {
        $params = array(
            'uids'         => $user_id,
            'access_token' => $token,
            'fields'       => 'uid,first_name,last_name',
        );

        $data = self::callApi('users.get', $params);

        if ($data) {
            return $data['response'][0];
        } else {
            return false;
        }
    }

    public static function getWallContent($user_id, $token)
    {
        $params = array(
            'owner_id'     => $user_id,
            'access_token' => $token,
        );

        $data = self::callApi('wall.get', $params);

        if ($data) {
            unset($data['response'][0]);
            return $data['response'];
        } else {
            return false;
        }
    }

    public static function getLikeList($user_id, $item_id, $type, $token)
    {
        $params = array(
            'owner_id' => $user_id,
            'item_id'  => $item_id,
            'type'     => $type,
            'access_token' => $token,
        );

        $response = json_decode(file_get_contents('https://api.vk.com/method/likes.getList' . '?' . urldecode(http_build_query($params))), true);

        if (empty($response['error'])) {
            return $response['response']['users'];
        } else {
            return false;
        }
    }

    public static function getCommentList($user_id, $item_id, $token)
    {
        $params = array(
            'owner_id' => $user_id,
            'post_id'  => $item_id,
            'access_token' => $token,
            'start_comment_id' => 0,
        );

        $response = json_decode(file_get_contents('https://api.vk.com/method/wall.getComments' . '?' . urldecode(http_build_query($params))), true);

        if (empty($response['error']) && $response['response'][0]) {
            unset($response['response'][0]);
            return $response['response'];
        } else {
            return false;
        }
    }

    public static function getRepostList($user_id, $item_id, $token)
    {
        $params = array(
            'owner_id' => $user_id,
            'post_id'  => $item_id,
            'access_token' => $token,
        );

        $response = json_decode(file_get_contents('https://api.vk.com/method/wall.getReposts' . '?' . urldecode(http_build_query($params))), true);

        if (empty($response['error'])) {
            return $response['response']['items'];
        } else {
            return false;
        }
    }

    public static function isMember($group_id, $user_id)
    {
        $params = array(
            'group_id' => $group_id,
            'user_id'  => $user_id,
        );

        $response = json_decode(file_get_contents('https://api.vk.com/method/groups.isMember' . '?' . urldecode(http_build_query($params))), true);
        return $response['response'];
    }
}