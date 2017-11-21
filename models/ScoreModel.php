<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 17.11.2017
 * Time: 13:30
 */
class ScoreModel
{
    private static $token = null;
    private static $standaloneToken = null;
    
    public static function init($token, $standaloneToken)
    {
        self::$token = $token;
        self::$standaloneToken = $standaloneToken;
    }
    
    public static function collect()
    {
        if (!self::$token) {
            return false;
        }

        $totalScores = 0;
        $userModel = new UserModel();
        $date = strtotime('last Monday', strtotime('next sunday')) + 3*3600;

        $break = false;
        $postOffset = 0;
        while(true) {
            $listPost = VkSdk::getWallContent('-' . Globals::$config->group_id, self::$token, $postOffset);
            if (!$listPost || $break) {
                break;
            }

            foreach ($listPost as $post) {
                if (!empty($post['is_pinned'])) {
                    continue;
                }
                if ($post['date'] < $date) {
                    $break = true;
                    break;
                }

                $offset = 0;
                $likeCount = 0;
                while(true) {
                    $listLikes = VkSdk::getLikeList('-' . Globals::$config->group_id, self::$token, $post['id'], 'post', $offset);
                    if (!$listLikes) {
                        break;
                    }

                    foreach ($listLikes as $user_id) {
                        $userEntity = $userModel->createFromSocialId($user_id, self::$token);
                        if (!$userEntity) {
                            break;
                        }

                        $activity = 'like';
                        if ($likeCount < 10) {
                            $activity = 'ten_like';
                        }
                        if (!$likeCount) {
                            $activity = 'first_like';
                        }

                        $actionEntity = new ActionEntity([
                            'user_id'          => $userEntity->id,
                            'user_social_id'   => $userEntity->social_id,
                            'social_id'        => $post['id'],
                            'parent_social_id' => $post['id'],
                            'activity'         => $activity,
                        ]);
                        $actionEntity->save();
                        $totalScores += $actionEntity->scores;
                        $likeCount++;
                    }
                    $offset += 100;
                }

                $offset = 0;
                while (true) {
                    $listComments = VkSdk::getCommentList('-' . Globals::$config->group_id, self::$standaloneToken, $post['id'], $offset);
                    if (!$listComments) {
                        break;
                    }

                    foreach ($listComments as $comment) {
                        $userEntity = $userModel->createFromSocialId($comment['from_id'], self::$token);
                        if (!$userEntity) {
                            break;
                        }

                        $actionEntity = new ActionEntity([
                            'user_id'          => $userEntity->id,
                            'user_social_id'   => $userEntity->social_id,
                            'social_id'        => $comment['id'],
                            'parent_social_id' => $post['id'],
                            'activity'         => 'comment',
                            'content'          => $comment['text'],
                        ]);
                        $actionEntity->save();
                        $totalScores += $actionEntity->scores;
                    }
                    $offset += 100;
                }

                $offset = 0;
                while (true) {
                    $listRepost = VkSdk::getRepostList('-' . Globals::$config->group_id, self::$token, $post['id'], $offset);
                    if (!$listRepost) {
                        break;
                    }

                    foreach ($listRepost as $repost) {
                        $userEntity = $userModel->createFromSocialId($repost['from_id'], self::$token);
                        if (!$userEntity) {
                            break;
                        }

                        $actionEntity = new ActionEntity([
                            'user_id' => $userEntity->id,
                            'user_social_id' => $userEntity->social_id,
                            'social_id' => $repost['id'],
                            'parent_social_id' => $post['id'],
                            'activity' => 'repost',
                        ]);
                        $actionEntity->save();
                        $totalScores += $actionEntity->scores;
                    }
                    $offset += 100;
                }
            }

            $postOffset += 100;
        }
        
        return $totalScores;
    }
    
    public static function updateTable()
    {
        $model = new UserModel();
        $data = $model->getAll();

        $message = '';
        $place = 1;
        foreach ($data as $user) {
            $message .= "$place. <a href='https://vk.com/id{$user['social_id']}'>{$user['name']}</a> - {$user['scores']}\n";
        }

        VkSdk::editTopic(self::$standaloneToken, $message);
    }
}