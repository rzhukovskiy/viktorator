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
    const SLEEP_TIME    = 0.3;

    private static $previousTime = 0;

    public static function addComment($group_id, $topic_id, $token, $message)
    {
        $params = [
            'group_id'     => $group_id,
            'topic_id'     => $topic_id,
            'from_group'   => 1,
            'access_token' => $token,
            'message'      => $message,
            'v'			   => '5.69',
        ];
        
        return self::callApiPost('board.createComment', $params);
    }

    public static function editTopic($group_id, $topic_id, $token, $message)
    {
        $params = [
            'group_id'     => $group_id,
            'topic_id'     => $topic_id,
            'comment_id'   => '118',
            'access_token' => $token,
            'message'      => $message,
            'v'			   => '5.69',
        ];

        $captchaError = ErrorModel::getCaptchaError();
        if ($captchaError && $captchaError->is_active && $captchaError->response) {
            $captchaError->is_active = 0;
            $captchaError->save();

            $data = unserialize($captchaError->content);
            $params['captcha_sid'] = $data['captcha_sid'];
            $params['captcha_key'] = $captchaError->response;
        }

        return self::callApiPost('board.editComment', $params);
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

    public static function getGroupAuthUrl($group_id)
    {
        $params = [
            'client_id'     => Globals::$config->app_id,
            'redirect_uri'  => Globals::$config->redirect_uri . "?Group[id]=$group_id",
            'response_type' => 'code',
            'scope'         => 'manage',
            'group_ids'     => $group_id,
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

        if (self::$previousTime > microtime(true) - self::SLEEP_TIME) {
            usleep(self::SLEEP_TIME);
        }
        self::$previousTime = microtime(true);

        $data = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

        if (empty($data['error'])) {
            return $data;
        } else {
            $errorEntity = new ErrorEntity([
                'type'      => 'access_token',
                'content'   => serialize($data['error'])
            ]);
            $errorEntity->save();
            return false;
        }
    }

    public static function getManagedGroupList($user_id, $token)
    {
        $params = array(
            'user_id'      => $user_id,
            'access_token' => $token,
            'count'        => 1000,
            'filter'       => 'admin',
        );

        return self::callApiWithOffset('groups.get', $params);
    }

    public static function getWallContent($owner_id, $token, $offset)
    {
        $params = array(
            'owner_id'     => $owner_id,
            'access_token' => $token,
            'count'        => 100,
            'offset'       => $offset,
        );

        return self::callApiWithOffset('wall.get', $params);
    }

    /**
     * @param string $owner_id
     * @param string $token
     * @param string $item_id
     * @param string $type
     * @param string $offset
     * @return array
     */
    public static function getLikeList($owner_id, $token, $item_id, $type, $offset)
    {
        $params = array(
            'owner_id'      => $owner_id,
            'item_id'       => $item_id,
            'type'          => $type,
            'access_token'  => $token,
            'offset'        => $offset,
            'count'         => 1000,
        );

        return self::callApiWithOffset('likes.getList', $params);
    }

    /**
     * @param string $owner_id
     * @param string $token
     * @param string $item_id
     * @param string $type
     * @return array
     */
    public static function getLikeWithRepostList($owner_id, $token, $item_id, $type)
    {
        $params = array(
            'owner_id'      => $owner_id,
            'item_id'       => $item_id,
            'type'          => $type,
            'access_token'  => $token,
            'filter'        => 'copies',
            'count'         => 1000,
        );

        return self::callApiWithOffset('likes.getList', $params);
    }

    /**
     * @param string $owner_id
     * @param string $token
     * @param string $post_id
     * @return array
     */
    public static function getCommentList($owner_id, $token, $post_id)
    {
        $params = array(
            'owner_id'          => $owner_id,
            'post_id'           => $post_id,
            'access_token'      => $token,
            'start_comment_id'  => 0,
            'need_likes'        => 1,
            'count'             => 100,
        );

        return self::callApiWithOffset('wall.getReposts', $params);
    }

    /**
     * @param string $owner_id
     * @param string $token
     * @param string $post_id
     * @return array
     */
    public static function getRepostList($owner_id, $token, $post_id)
    {
        $params = array(
            'owner_id'      => $owner_id,
            'post_id'       => $post_id,
            'access_token'  => $token,
            'count'         => 100,
        );

        return self::callApiWithOffset('wall.getReposts', $params);
    }

    /**
     * @param string $user_ids
     * @param string $token
     * @return array|bool
     */
    public static function getUser($user_ids, $token)
    {
        $params = array(
            'user_ids' => $user_ids,
            'access_token' => $token,
        );

        $data = self::callApi('users.get', $params);

        return isset($data['response'][0]) ? $data['response'][0] : false;
    }

    /**
     * @param $group_id
     * @param $user_id
     * @return bool|array
     */
    public static function isMember($group_id, $user_id)
    {
        $params = array(
            'group_id' => $group_id,
            'user_id'  => $user_id,
        );

        $data = self::callApi('groups.isMember', $params);

        return $data ? $data['response'] : false;
    }


    /**
     * @param string $method
     * @param string $params
     * @return array|bool
     */
    private static function callApi($method, $params)
    {
        $url = self::API_URL . $method . '?' . urldecode(http_build_query($params)) . '&v=' . self::API_VERSION;

        if (self::$previousTime > microtime(true) - self::SLEEP_TIME) {
            usleep(self::SLEEP_TIME * 1000000);
        }
        self::$previousTime = microtime(true);

        do {
            $data = json_decode(file_get_contents($url), true);

            if (empty($data['error'])) {
                return $data;
            } elseif($data['error']['error_code'] != 6) {
                $errorEntity = new ErrorEntity([
                    'type'      => $method,
                    'content'   => serialize($data['error'])
                ]);
                $errorEntity->save();
                return false;
            }
            usleep(self::SLEEP_TIME * 1000000);
        } while($data['error']['error_code'] == 6);
        
        return false;
    }

    /**
     * @param string $method
     * @param array  $params
     * @return array
     */
    private static function callApiWithOffset($method, $params)
    {
        $res = [];
        $i = 0;
        $params['offset'] = 0;
        while (true) {
            $data = self::callApi($method, $params);

            if ($data) {
                $res = array_merge($res, $data['response']['items']);
            } else {
                break;
            }

            if ($data['response']['count'] <= $i * $params['count']) {
                break;
            }

            $params['offset'] += $params['count'];
            $i++;
        }

        return $res;
    }

    /**
     * @param string $method
     * @param array  $params
     * @return array
     */
    private static function callApiPost($method, $params)
    {
        $url = self::API_URL . $method;

        if (self::$previousTime > microtime(true) - self::SLEEP_TIME) {
            usleep(self::SLEEP_TIME);
        }
        self::$previousTime = microtime(true);

        $data = json_decode(file_get_contents($url, false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        ))), true);

        if (empty($data['error'])) {
            return $data;
        } else {
            $errorEntity = new ErrorEntity([
                'type'      => $method,
                'content'   => serialize($data['error'])
            ]);
            $errorEntity->save();
            return false;
        }
    }
}