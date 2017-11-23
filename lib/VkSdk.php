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
    const API_VERSION   = '5.69';

    private static function callApi($method, $params)
    {
        $url = self::API_URL . $method . '?' . urldecode(http_build_query($params)) . '&v=' . self::API_VERSION;
        $data = json_decode(file_get_contents($url), true);

        if (empty($data['error'])) {
            return $data;
        } else {
            $errorEntity = new ErrorEntity([
                'type'      => 'vk',
                'content'   => print_r($data['error'], true)
            ]);
            $errorEntity->save();
            return false;
        }
    }

    public static function addComment($token, $message)
    {
        $params = [
            'group_id'     => Globals::$config->group_id,
            'topic_id'     => Globals::$config->topic_id,
            'from_group'   => 1,
            'access_token' => $token,
            'message'      => $message,
            'v'			   => '5.69',
        ];

        $url = 'https://api.vk.com/method/board.createComment';
        $result = json_decode(file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        ))), true);
        
        if (!empty($result['error'])) {
            $errorEntity = new ErrorEntity([
                'type'      => 'vk',
                'content'   => print_r($result['error'], true)
            ]);
            $errorEntity->save();
        }

        return $result;
    }

    public static function editTopic($token, $message)
    {
        $params = [
            'group_id'     => Globals::$config->group_id,
            'topic_id'     => Globals::$config->topic_id,
            'comment_id'   => '118',
            'access_token' => $token,
            'message'      => $message,
            'v'			   => '5.69',
        ];

        $url = 'https://api.vk.com/method/board.editComment';
        $result = json_decode(file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        ))), true);

        if (!empty($result['error'])) {
            $errorEntity = new ErrorEntity([
                'type'      => 'vk',
                'content'   => print_r($result['error'], true)
            ]);
            $errorEntity->save();
        }

        return $result;
    }

    public static function getAuthUrl()
    {
        $params = [
            'client_id'     => Globals::$config->app_id,
            'redirect_uri'  => Globals::$config->redirect_uri,
            'response_type' => 'code',
            'scope'         => 'offline,wall,notify,friends,groups',
        ];
        return 'http://oauth.vk.com/authorize?' . urldecode(http_build_query($params));
    }

    public static function getStandaloneAuthUrl()
    {
        $params = [
            'client_id'     => Globals::$config->standalone_id,
            'response_type' => 'token',
            'scope'         => 'offline,wall,notify,friends,groups',
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
            ErrorModel::save([
                'type'      => 'vk',
                'content'   => $infoToken['error']
            ]);
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

    public static function getWallContent($owner_id, $token, $offset)
    {
        $params = array(
            'owner_id'     => $owner_id,
            'access_token' => $token,
            'count'        => 100,
            'offset'       => $offset,
        );

        $data = self::callApi('wall.get', $params);

        if ($data) {
            return $data['response']['items'];
        } else {
            return false;
        }
    }

    public static function getLikeList($owner_id, $token, $item_id, $type, $offset)
    {
        $params = array(
            'owner_id'      => $owner_id,
            'item_id'       => $item_id,
            'type'          => $type,
            'access_token'  => $token,
            'offset'        => $offset,
            'count'         => 100,
        );

        $data = self::callApi('likes.getList', $params);

        if ($data) {
            return $data['response']['items'];
        } else {
            return false;
        }
    }

    public static function getCommentList($owner_id, $token, $post_id, $offset)
    {
        $params = array(
            'owner_id'          => $owner_id,
            'post_id'           => $post_id,
            'access_token'      => $token,
            'start_comment_id'  => 0,
            'need_likes'        => 1,
            'offset'            => $offset,
            'count'             => 100,
        );

        $data = self::callApi('wall.getComments', $params);

        if ($data) {
            return $data['response']['items'];
        } else {
            return false;
        }
    }

    public static function getRepostList($owner_id, $token, $post_id, $offset)
    {
        $params = array(
            'owner_id'      => $owner_id,
            'post_id'       => $post_id,
            'access_token'  => $token,
            'offset'        => $offset,
            'count'         => 100,
        );

        $data = self::callApi('wall.getReposts', $params);

        if ($data) {
            return $data['response']['items'];
        } else {
            return false;
        }
    }

    public static function getUser($user_ids, $token)
    {
        $params = array(
            'user_ids' => $user_ids,
            'access_token' => $token,
        );

        $data = self::callApi('users.get', $params);

        if ($data) {
            return isset($data['response'][0]) ? $data['response'][0] : false;
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

        $data = self::callApi('groups.isMember', $params);

        if ($data) {
            return $data['response'];
        } else {
            return false;
        }
    }
}