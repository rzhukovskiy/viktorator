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
        $date = new DateTime();
        $date->setTimestamp(strtotime('this week'))->setTime(0, 0, 0);
        $date = $date->getTimestamp() - 3 * 3600;
        if ((7 * 24 * 3600 - time() + $date) <= 3 * 3600) {
            $date = new DateTime();
            $date->setTimestamp(strtotime('next week'))->setTime(0, 0, 0);
            $date = $date->getTimestamp() - 3 * 3600;
        } else {
            $date -= 3 * 3600;
        }

        $listUser = UserModel::getAll();
        $listAction = ActionModel::getAll();

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

                $postAuthor = null;
                if ($post['from_id'] != '-' . Globals::$config->group_id) {
                    if (!isset($listUser[$post['from_id']])) {
                        $postAuthor = UserModel::createFromSocialId($post['from_id'], self::$token);
                        $listUser[$post['from_id']] = $postAuthor;
                    }
                    $postAuthor = $listUser[$post['from_id']];
                }

                $offset = 0;
                $likeCount = 0;
                while(true) {
                    $listLikes = VkSdk::getLikeList('-' . Globals::$config->group_id, self::$token, $post['id'], 'post', $offset);
                    if (!$listLikes) {
                        break;
                    }

                    foreach ($listLikes as $user_id) {
                        if ($post['from_id'] == $user_id) {
                            continue;
                        }
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
                        if (!isset($listAction[$actionEntity->user_id][$activity][$actionEntity->parent_social_id][$actionEntity->social_id])) {
                            $actionEntity->save();
                            $totalScores += $actionEntity->scores;
                        }
                        
                        if ($postAuthor && $postAuthor->id != $user_id) {
                            $activity = 'post_like';
                            $actionEntity = new ActionEntity([
                                'user_id'          => $postAuthor->id,
                                'user_social_id'   => $postAuthor->social_id,
                                'social_id'        => $user_id,
                                'parent_social_id' => $post['id'],
                                'activity'         => $activity,
                            ]);
                            if (!isset($listAction[$actionEntity->user_id][$activity][$actionEntity->parent_social_id][$actionEntity->social_id])) {
                                $actionEntity->save();
                                $totalScores += $actionEntity->scores;
                            }
                        }
                        
                        $likeCount++;
                    }
                    $offset += 1000;
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
                        if (!isset($listAction[$actionEntity->user_id]['comment'][$actionEntity->parent_social_id][$actionEntity->social_id])) {
                            $actionEntity->save();
                            $totalScores += $actionEntity->scores;
                        }

                        if ($comment['likes']['count'] > 0) {
                            $offset = 0;
                            $likeCount = 0;
                            while(true) {
                                $listLikes = VkSdk::getLikeList('-' . Globals::$config->group_id, self::$token, $comment['id'], 'comment', $offset);
                                if (!$listLikes) {
                                    break;
                                }

                                foreach ($listLikes as $user_id) {
                                    if ($comment['from_id'] == $user_id) {
                                        continue;
                                    }
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
                                    if (!isset($listAction[$actionEntity->user_id][$activity][$actionEntity->parent_social_id][$actionEntity->social_id])) {
                                        $actionEntity->save();
                                        $totalScores += $actionEntity->scores;
                                    }
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
        $data = UserModel::getTop(24);

        $message = '';
        $place = 1;
        foreach ($data as $user) {
            $message .= "$place. [id{$user->social_id}|{$user->name}] - {$user->scores}\n";
            if ($place != count($data) && !($place % 12)) {
                $message .= "----------------------------------------------\n";
            }
            $place++;
        }

        VkSdk::editTopic(self::$standaloneToken, $message);
    }
}