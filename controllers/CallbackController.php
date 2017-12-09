<?php

/**
 */
class CallbackController extends BaseController
{
    /** @var $admin BotEntity  */
    private $bot   = null;

    public function init()
    {
        $this->bot = new BotEntity();
        parent::init();
    }

    /**
     * @return null
     */
    public function actionVk()
    {
        $data = json_decode(file_get_contents('php://input'));

        if ($data->type == 'board_post_new'
            && $data->secret == Globals::$config->group_secret
            && $data->object->from_id != ('-' . Globals::$config->group_id)
        ) {
            $userEntity = UserModel::createFromSocialId($data->object->from_id, $this->bot->getToken());

            if (!$userEntity->is_member && VkSdk::isMember($userEntity->group_id, $userEntity->social_id)) {
                $userEntity->is_member = 1;
                $userEntity->save();
            }

            if (!$userEntity->is_repost) {
                $offset = 0;
                while (true) {
                    $listLike = VkSdk::getLikeWithRepostList(
                        '-' . $userEntity->group_id,
                        $this->bot->getToken(),
                        Globals::$config->post_id,
                        'post',
                        $offset
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
            $allLikeScores         = isset($data['all_like']) ? $data['all_like'] : 0;

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
            VkSdk::addComment(Globals::$config->standalone_token, $message);

            echo 'ok';
            exit();
        } elseif ($data->type == 'confirm') {
            echo Globals::$config->group_confirm;
            exit();
        } else {
            echo 'ok';
            exit();
        }
    }
}