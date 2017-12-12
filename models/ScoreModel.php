<?php

/**
 */
class ScoreModel
{
    /**
     * @param PublicEntity $publicEntity
     * @param int $startDate
     * @param int $endDate
     * @return bool|int
     */
    public static function collect($publicEntity, $startDate, $endDate)
    {
        $adminEntity = $publicEntity->getAdmin();

        $totalScores = 0;

        $listUser      = UserModel::getAll($publicEntity->id);
        $listAction    = ActionModel::getAll($publicEntity->id);
        $listSavedPost = PostModel::getAllAfterDate($publicEntity->id, $startDate);

        $listPost = VkSdk::getWallContentAfterDate('-' . $publicEntity->id, $startDate, $adminEntity->token);

        foreach ($listPost as $post) {
            if (!empty($post['is_pinned'])) {
                continue;
            }
            if ($post['date'] > $endDate) {
                continue;
            }
            if ($post['date'] < $startDate) {
                break;
            }
            
            $postEntity = $listSavedPost[$post['id']];

            $postAuthor = null;
            if ($post['from_id'] != '-' . $publicEntity->id) {
                if (!isset($listUser[$post['from_id']])) {
                    $postAuthor = UserModel::createFromSocialId($post['from_id'], $publicEntity->id, $publicEntity->post_id, $adminEntity->token);
                    $listUser[$post['from_id']] = $postAuthor;
                }
                $postAuthor = $listUser[$post['from_id']];
            }

            $offset = 0;
            $likeCount = 0;
            if ($post['likes']['count'] && $postEntity->likes != $post['likes']['count']) {
                $listLikes = VkSdk::getLikeList('-' . $publicEntity->id, $post['id'], 'post', $adminEntity->token);

                foreach ($listLikes as $user_id) {
                    if ($post['from_id'] == $user_id) {
                        continue;
                    }
                    if (!isset($listUser[$user_id])) {
                        $userEntity = UserModel::createFromSocialId($user_id, $publicEntity->id, $publicEntity->post_id, $adminEntity->token);
                        if (!$userEntity) {
                            continue;
                        }
                        $listUser[$user_id] = $userEntity;
                    }
                    $userEntity = $listUser[$user_id];

                    $activity = ActivityModel::NAME_LIKE;
                    if ($likeCount > $offset + count($listLikes) - 11) {
                        $activity = ActivityModel::NAME_TEN_LIKE;
                    }
                    if ($likeCount == $offset + count($listLikes) - 1) {
                        $activity = ActivityModel::NAME_FIRST_LIKE;
                    }

                    $actionEntity = new ActionEntity([
                        'group_id'         => $publicEntity->id,
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
                        $activity = ActivityModel::NAME_POST_LIKE;
                        $actionEntity = new ActionEntity([
                            'group_id'         => $publicEntity->id,
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
            }

            $listSavedComment = CommentModel::getAllByPost($postEntity->id);
            if ($post['comments']['count']) {
                $listComments = VkSdk::getCommentList('-' . $publicEntity->id, $post['id'], $publicEntity->standalone_token);

                foreach ($listComments as $comment) {
                    if (!isset($listUser[$comment['from_id']])) {
                        $userEntity = UserModel::createFromSocialId($comment['from_id'], $publicEntity->id, $publicEntity->post_id, $adminEntity->token);
                        if (!$userEntity) {
                            continue;
                        }
                        $listUser[$comment['from_id']] = $userEntity;
                    }
                    
                    $userEntity = $listUser[$comment['from_id']];
                    $commentEntity = $listSavedComment[$comment['id']];

                    $likeCount = 0;
                    if ($comment['likes']['count'] && $commentEntity->likes != $comment['likes']['count']) {
                        $listLikes = VkSdk::getLikeList('-' . $publicEntity->id, $comment['id'], 'comment', $adminEntity->token);
                        if (!$listLikes) {
                            break;
                        }

                        foreach ($listLikes as $user_id) {
                            if ($comment['from_id'] == $user_id) {
                                continue;
                            }
                            $activity = ActivityModel::NAME_COMMENT_LIKE;
                            $target_id = $userEntity->id;
                            $source_id = $user_id;
                            if ($user_id == $post['from_id']) {
                                $activity = ActivityModel::NAME_AUTHOR_LIKE;
                                $target_id = $user_id;
                                $source_id = $userEntity->id;
                            }

                            $actionEntity = new ActionEntity([
                                'group_id'         => $publicEntity->id,
                                'user_id'          => $target_id,
                                'user_social_id'   => $userEntity->social_id,
                                'social_id'        => $source_id,
                                'parent_social_id' => $comment['id'],
                                'activity'         => $activity,
                            ]);
                            if (!isset($listAction[$actionEntity->user_id][$activity][$actionEntity->parent_social_id][$actionEntity->social_id])) {
                                $actionEntity->save();
                                $totalScores += $actionEntity->scores;
                            }
                            $likeCount++;
                        }
                    }
                    $commentEntity->likes = $comment['likes']['count'];
                    $commentEntity->save();
                }
            }

            $postEntity->likes    = $post['likes']['count'];
            $postEntity->comments = $post['comments']['count'];
            $postEntity->reposts  = $post['reposts']['count'];
            $postEntity->save();
        }

        return $totalScores;
    }

    /**
     * @param PublicEntity $publicEntity
     */
    public static function updateTable($publicEntity)
    {
        $data = UserModel::getTop($publicEntity->id, 24);

        $message = '';
        $place = 1;
        foreach ($data as $user) {
            $message .= "$place. [id{$user->social_id}|{$user->name}] - {$user->scores}\n";
            if ($place != count($data) && !($place % 12)) {
                $message .= "----------------------------------------------\n";
            }
            $place++;
        }

        VkSdk::editTopic($publicEntity->id, $publicEntity->topic_id, $message, $publicEntity->standalone_token);
    }

    /**
     * @param PublicEntity $publicEntity
     * @param int $startDate
     * @param int $endDate
     * @return bool
     */
    public static function collectDaily($publicEntity, $startDate, $endDate)
    {
        $adminEntity = $publicEntity->getAdmin();

        $listUser = UserModel::getAll($publicEntity->id);

        $likedAll = [];

        $postCount = 0;
        $listPost = VkSdk::getWallContentAfterDate('-' . $publicEntity->id, $startDate, $adminEntity->token);
        foreach ($listPost as $post) {
            if (!empty($post['is_pinned'])) {
                continue;
            }
            if ($post['date'] > $endDate) {
                continue;
            }
            if ($post['date'] < $startDate) {
                break;
            }

            //автора поста считаем автоматически лайкнувшим свой пост
            if ($post['from_id'] != '-' . $publicEntity->id) {
                $likedCurrent['from_id'] = 1;
            }

            $likedCurrent = [];
            $listLikes = VkSdk::getLikeList('-' . $publicEntity->id, $post['id'], 'post', $adminEntity->token);
            foreach ($listLikes as $user_id) {
                //все-таки вряд ли юзер, который до сих пор не попал в базу мог пролайкать все посты
                if (!isset($listUser[$user_id])) {
                    continue;
                }

                $likedCurrent[$user_id] = 1;
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

        if (!empty($likedAll)) {
            foreach ($likedAll as $user_id => $value) {
                $activity = 'all_like';

                $actionEntity = new ActionEntity([
                    'group_id'         => $publicEntity->id,
                    'user_id'          => $listUser[$user_id]->id,
                    'user_social_id'   => $listUser[$user_id]->social_id,
                    'social_id'        => $listUser[$user_id]->social_id,
                    'parent_social_id' => date('Ymd', $startDate),
                    'activity'         => $activity,
                ]);
                $actionEntity->save();
            }
        }

        return true;
    }
}
