<?php

/**
 */
class CallbackModel
{
    /**
     * @param PublicEntity $publicEntity
     * @param object $data
     */
    public static function newTopicComment($publicEntity, $data)
    {
        if ($data->object->from_id == ('-' . $publicEntity->id)) {
            return;
        }
        $adminEntity = $publicEntity->getAdmin();

        $userEntity = UserModel::createFromSocialId($data->object->from_id, $publicEntity->id, $publicEntity->post_id, $adminEntity->token);

        if (!$userEntity->is_member && VkSdk::isMember($userEntity->group_id, $userEntity->social_id)) {
            $userEntity->is_member = 1;
            $userEntity->save();
        }

        $data = ActionModel::getScores($userEntity->id);

        $likeScores         = isset($data[ActivityModel::NAME_LIKE])         ? $data[ActivityModel::NAME_LIKE] : 0;
        $tenLikeScores      = isset($data[ActivityModel::NAME_TEN_LIKE])     ? $data[ActivityModel::NAME_TEN_LIKE] : 0;
        $firstLikeScores    = isset($data[ActivityModel::NAME_FIRST_LIKE])   ? $data[ActivityModel::NAME_FIRST_LIKE] : 0;
        $postLikeScores     = isset($data[ActivityModel::NAME_POST_LIKE])    ? $data[ActivityModel::NAME_POST_LIKE] : 0;
        $commentScores      = isset($data[ActivityModel::NAME_COMMENT])      ? $data[ActivityModel::NAME_COMMENT] : 0;
        $commentLikeScores  = isset($data[ActivityModel::NAME_COMMENT_LIKE]) ? $data[ActivityModel::NAME_COMMENT_LIKE] : 0;
        $authorLike         = isset($data[ActivityModel::NAME_AUTHOR_LIKE])  ? $data[ActivityModel::NAME_AUTHOR_LIKE] : 0;
        $allLikeScores      = isset($data[ActivityModel::NAME_ALL_LIKE])     ? $data[ActivityModel::NAME_ALL_LIKE] : 0;

        if ($userEntity->is_member) {
            $message = "[id$userEntity->social_id|$userEntity->name], ваши баллы:\n"
                . " - лайк поста - $likeScores\n"
                . " - лайк поста первым - $firstLikeScores\n"
                . " - лайк поста среди первых - $tenLikeScores\n"
                . " - лайки ваших постов - $postLikeScores\n"
                . " - комментарий по теме поста - $commentScores\n"
                . " - комментарий, который набирает лайки - $commentLikeScores\n"
                . " - лайк от автора поста - $authorLike\n"
                . " - лайки ко всем постам в течение дня - $allLikeScores\n";
        } else {
            $message = "[id$userEntity->social_id|$userEntity->name], Вы не являетесь участником сообщества. Данные по количествам баллов недоступны. Сначала вступите :)";
        }
        VkSdk::addComment($publicEntity->id, $publicEntity->topic_id, $publicEntity->standalone_token, $message);
        
        return;
    }

    /**
     * @param PublicEntity $publicEntity
     * @param object $data
     */
    public static function newPost($publicEntity, $data)
    {
        $adminEntity = $publicEntity->getAdmin();
        $userEntity = UserModel::createFromSocialId($data->object->from_id, $publicEntity->id, $publicEntity->post_id, $adminEntity->token);
        if (!$userEntity) {
            return;
        }
        
        $postEntity = new PostEntity([
            'group_id'  => $publicEntity->id,
            'social_id' => $data->object->id,
            'likes' => 0,
            'comments' => 0,
            'reposts' => 0,
        ]);
        $postEntity->save();
    }

    /**
     * @param PublicEntity $publicEntity
     * @param object $data
     */
    public static function newRepost($publicEntity, $data)
    {
        $adminEntity = $publicEntity->getAdmin();
        $userEntity = UserModel::createFromSocialId($data->object->from_id, $publicEntity->id, $publicEntity->post_id, $adminEntity->token);
        if (!$userEntity) {
            return;
        }
        
        if ($data->object->id == $publicEntity->post_id) {
            $userEntity->is_repost = 1;
            $userEntity->save();
        }
    }

    /**
     * @param PublicEntity $publicEntity
     * @param object $data
     */
    public static function newPostComment($publicEntity, $data)
    {
        if ($data->object->from_id == ('-' . $publicEntity->id)) {
            return;
        }
        $adminEntity = $publicEntity->getAdmin();

        $userEntity = UserModel::createFromSocialId($data->object->from_id, $publicEntity->id, $publicEntity->post_id, $adminEntity->token);
        if (!$userEntity) {
            return;
        }

        $postEntity = PostModel::getByGroupAndSocialId($publicEntity->id, $data->object->post_id);
        if (!$postEntity) {
            $postEntity = new PostEntity([
                'group_id'  => $publicEntity->id,
                'social_id' => $data->object->post_id,
                'likes' => 0,
                'comments' => 0,
                'reposts' => 0,
            ]);
            $postEntity->save();
        }

        $commentEntity = new CommentEntity([
            'post_id'   => $postEntity->id,
            'group_id'  => $publicEntity->id,
            'social_id' => $data->object->id,
            'likes'     => 0,
        ]);
        $commentEntity->save();

        $actionEntity = new ActionEntity([
            'group_id'         => $publicEntity->id,
            'user_id'          => $userEntity->id,
            'user_social_id'   => $userEntity->social_id,
            'social_id'        => $data->object->id,
            'parent_social_id' => $data->object->post_id,
            'activity'         => ActivityModel::NAME_COMMENT,
            'content'          => $data->object->text,
        ]);
        $actionEntity->save();
    }

    /**
     * @param object $data
     */
    public static function removePostComment($data)
    {
        $actionEntity = ActionModel::checkByActivity(
            ActivityModel::getByName(ActivityModel::NAME_COMMENT),
            $data->object->id,
            $data->object->post_id,
            $data->object->from_id
        );
        
        if ($actionEntity) {
            $actionEntity->deactivate();
        }
    }

    /**
     * @param object $data
     */
    public static function restorePostComment($data)
    {
        $actionEntity = ActionModel::checkByActivity(
            ActivityModel::getByName(ActivityModel::NAME_COMMENT),
            $data->object->id,
            $data->object->post_id,
            $data->object->from_id
        );

        if ($actionEntity) {
            $actionEntity->activate();
        }
    }
}