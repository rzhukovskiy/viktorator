<?php

/**
 */
class CallbackController extends BaseController
{
    /**
     * @return null
     */
    public function actionVk()
    {
        $data = json_decode(file_get_contents('php://input'));

        if ($data->type == 'board_post_new') {
            $publicEntity = PublicModel::getById($data->topic_owner_id);
            if (!$publicEntity || $data->secret != $publicEntity->secret || $data->object->from_id == ('-' . $publicEntity->id)) {
                $this->exitOk();
            }
            $adminEntity = $publicEntity->getAdmin();

            $userEntity = UserModel::createFromSocialId($data->object->from_id, $publicEntity->id, $publicEntity->post_id, $adminEntity->token);

            if (!$userEntity->is_member && VkSdk::isMember($userEntity->group_id, $userEntity->social_id)) {
                $userEntity->is_member = 1;
                $userEntity->save();
            }

            if (!$userEntity->is_repost) {
                $offset = 0;
                while (true) {
                    $listLike = VkSdk::getLikeWithRepostList(
                        '-' . $userEntity->group_id,
                        $publicEntity->post_id,
                        'post',
                        $adminEntity->token
                    );

                    if(!$listLike) {
                        break;
                    }
                    foreach ($listLike as $user_id) {
                        if ($user_id == $userEntity->social_id) {
                            $userEntity->is_repost = 1;
                            $userEntity->save();
                            break;
                        }
                    }
                    $offset += 100;
                }
            }
            
            $data = ActionModel::getScores($userEntity->id);

            $likeScores         = isset($data['like']) ? $data['like'] : 0;
            $tenLikeScores      = isset($data['ten_like']) ? $data['ten_like'] : 0;
            $firstLikeScores    = isset($data['first_like']) ? $data['first_like'] : 0;
            $postLikeScores     = isset($data['post_like']) ? $data['post_like'] : 0;
            $commentScores      = isset($data['comment']) ? $data['comment'] : 0;
            $commentLikeScores  = isset($data['comment_like']) ? $data['comment_like'] : 0;
            $authorLike         = isset($data['author_like']) ? $data['author_like'] : 0;
            $allLikeScores      = isset($data['all_like']) ? $data['all_like'] : 0;

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

            $this->exitOk();
        } elseif ($data->type == 'confirmation') {
            $publicEntity = PublicModel::getById($data->group_id);
            echo $publicEntity->confirm;
            exit();
        } else {
            $this->exitOk();
        }
    }

    private function exitOk()
    {
        echo 'ok';
        exit();
    }
}