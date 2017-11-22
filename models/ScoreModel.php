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
        $date = strtotime('last Monday', strtotime('next sunday')) + 3*3600;
        $listUser = UserModel::getAll();

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
                        if (!isset($listUser[$user_id])) {
                            $userEntity = UserModel::createFromSocialId($user_id, self::$token);
                            if (!$userEntity) {
                                break;
                            }
                            $listUser[$user_id] = $userEntity;
                        }
                        $userEntity = $listUser[$user_id];

                        $activity = 'like';
                        if ($likeCount > $offset + count($listLikes) - 11) {
                            $activity = 'ten_like';
                        }
                        if ($likeCount == $offset + count($listLikes) - 1) {
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
                        if (!isset($listUser[$comment['from_id']])) {
                            $userEntity = UserModel::createFromSocialId($comment['from_id'], self::$token);
                            if (!$userEntity) {
                                break;
                            }
                            $listUser[$comment['from_id']] = $userEntity;
                        }
                        $userEntity = $listUser[$comment['from_id']];

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

                        if ($comment['likes']['count'] > 0) {
                            $offset = 0;
                            $likeCount = 0;
                            while(true) {
                                $listLikes = VkSdk::getLikeList('-' . Globals::$config->group_id, self::$token, $comment['id'], 'comment', $offset);
                                if (!$listLikes) {
                                    break;
                                }

                                foreach ($listLikes as $user_id) {
                                    $activity = 'comment_like';
                                    if ($user_id == $post['from_id']) {
                                        $activity = 'author_like';
                                    }

                                    $actionEntity = new ActionEntity([
                                        'user_id'          => $userEntity->id,
                                        'user_social_id'   => $userEntity->social_id,
                                        'social_id'        => $user_id,
                                        'parent_social_id' => $comment['id'],
                                        'activity'         => $activity,
                                    ]);
                                    $actionEntity->save();
                                    $totalScores += $actionEntity->scores;
                                    $likeCount++;
                                }
                                $offset += 100;
                            }
                        }
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
        $data = UserModel::getTop(12);

        $message = '';
        $place = 1;
        foreach ($data as $user) {
            $message .= "$place. [id{$user->social_id}|{$user->name}] - {$user->scores}\n";
            $place++;
        }

        VkSdk::editTopic(self::$standaloneToken, $message);
    }
}