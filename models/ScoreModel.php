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

    /**
     * @param int $group_id
     * @param int $startDate
     * @param int $endDate
     * @return bool|int
     */
    public static function collect($group_id, $startDate, $endDate)
    {
        if (!self::$token) {
            return false;
        }

        $totalScores = 0;

        $listUser      = UserModel::getAll();
        $listAction    = ActionModel::getAll();
        $listSavedPost = PostModel::getAllAfterDate($group_id, $startDate);

        $break = false;
        $postOffset = 0;
        while(!$break) {
            $listPost = VkSdk::getWallContent('-' . $group_id, self::$token, $postOffset);
            if (!$listPost) {
                break;
            }

            foreach ($listPost as $post) {
                if (!empty($post['is_pinned'])) {
                    continue;
                }
                if ($post['date'] > $endDate) {
                    continue;
                }
                if ($post['date'] < $startDate) {
                    $break = true;
                    break;
                }

                if (!isset($listSavedPost[$post['id']])) {
                    $listSavedPost[$post['id']] = new PostEntity([
                        'group_id'  => $group_id,
                        'social_id' => $post['id'],
                        'likes' => 0,
                        'comments' => 0,
                        'reposts' => 0,
                    ]);
                    $listSavedPost[$post['id']]->save();
                }
                $postEntity = $listSavedPost[$post['id']];

                $postAuthor = null;
                if ($post['from_id'] != '-' . $group_id) {
                    if (!isset($listUser[$post['from_id']])) {
                        $postAuthor = UserModel::createFromSocialId($post['from_id'], self::$token);
                        $listUser[$post['from_id']] = $postAuthor;
                    }
                    $postAuthor = $listUser[$post['from_id']];
                }

                $offset = 0;
                $likeCount = 0;
                while($post['likes']['count'] && $postEntity->likes != $post['likes']['count']) {
                    $listLikes = VkSdk::getLikeList('-' . $group_id, self::$token, $post['id'], 'post', $offset);
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
                                continue;
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

                    if (count($listLikes) != 1000) {
                        break;
                    }

                    $offset += 1000;
                }

                $listSavedComment = CommentModel::getAllByPost($postEntity->id);
                $offset = 0;
                while ($post['comments']['count']) {
                    $listComments = VkSdk::getCommentList('-' . $group_id, self::$standaloneToken, $post['id'], $offset);
                    if (!$listComments) {
                        break;
                    }

                    foreach ($listComments as $comment) {
                        if (!isset($listUser[$comment['from_id']])) {
                            $userEntity = UserModel::createFromSocialId($comment['from_id'], self::$token);
                            if (!$userEntity) {
                                continue;
                            }
                            $listUser[$comment['from_id']] = $userEntity;
                        }
                        $userEntity = $listUser[$comment['from_id']];

                        if (!isset($listSavedComment[$comment['id']])) {
                            $listSavedComment[$comment['id']] = new CommentEntity([
                                'post_id'   => $postEntity->id,
                                'group_id'  => $group_id,
                                'social_id' => $comment['id'],
                                'likes'     => 0,
                            ]);
                            $listSavedComment[$comment['id']]->save();

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
                        }
                        $commentEntity = $listSavedComment[$comment['id']];

                        $offsetCommentLikes = 0;
                        $likeCount = 0;
                        while($comment['likes']['count'] && $commentEntity->likes != $comment['likes']['count']) {
                            $listLikes = VkSdk::getLikeList(
                                '-' . $group_id,
                                self::$token, $comment['id'],
                                'comment', $offsetCommentLikes
                            );
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

                            if (count($listLikes) != 1000) {
                                break;
                            }
                            $offsetCommentLikes += 1000;
                        }
                        $commentEntity->likes = $comment['likes']['count'];
                        $commentEntity->save();
                    }

                    if (count($listComments) != 100) {
                        break;
                    }
                    $offset += 100;
                }

                $postEntity->likes    = $post['likes']['count'];
                $postEntity->comments = $post['comments']['count'];
                $postEntity->reposts  = $post['reposts']['count'];
                $postEntity->save();
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

    public static function collectDaily($beginOfDay)
    {
        if (!self::$token) {
            return false;
        }

        $listUser = UserModel::getAll();

        $likedAll = [];
        $break = false;
        $postOffset = 0;
        $postCount = 0;
        while(true) {
            $listPost = VkSdk::getWallContent('-' . Globals::$config->group_id, self::$token, $postOffset);
            if (!$listPost || $break) {
                break;
            }

            foreach ($listPost as $post) {
                if (!empty($post['is_pinned'])) {
                    continue;
                }
                if ($post['date'] < $beginOfDay) {
                    $break = true;
                    break;
                }

                $offset = 0;
                $likedCurrent = [];
                while (true) {
                    $listLikes = VkSdk::getLikeList('-' . Globals::$config->group_id, self::$token, $post['id'], 'post', $offset);
                    if (!$listLikes) {
                        break;
                    }
                    //автора поста считаем автоматически лайкнувшим свой пост
                    if ($post['from_id'] != '-' . Globals::$config->group_id) {
                        $likedCurrent['from_id'] = 1;
                    }

                    foreach ($listLikes as $user_id) {
                        //все-таки вряд ли юзер, который до сих пор не попал в базу мог пролайкать все посты
                        if (!isset($listUser[$user_id])) {
                            continue;
                        }

                        $likedCurrent[$user_id] = 1;
                    }
                    $offset += 1000;
                }

                if (!$postCount) {
                    $likedAll = $likedCurrent;
                } else {
                    $likedAll = array_intersect_assoc($likedAll, $likedCurrent);
                }
                //если в текущую итерацию не осталось юзеров, пролайкавших и текущий и прошлый пост не нашлось - дальше искать нет смысла
                if (empty($likedAll)) {
                    break;
                }
                $postCount++;
            }

            //если в текущую итерацию не осталось юзеров, пролайкавших и текущий и прошлый пост не нашлось - дальше искать нет смысла
            if (empty($likedAll)) {
                break;
            }

            $postOffset += 100;
        }

        if (!empty($likedAll)) {
            foreach ($likedAll as $user_id => $value) {
                $activity = 'all_like';

                $actionEntity = new ActionEntity([
                    'user_id'          => $listUser[$user_id]->id,
                    'user_social_id'   => $listUser[$user_id]->social_id,
                    'social_id'        => $listUser[$user_id]->social_id,
                    'parent_social_id' => date('Ymd', $beginOfDay),
                    'activity'         => $activity,
                ]);
                $actionEntity->save();
            }
        }

        return true;
    }
}
