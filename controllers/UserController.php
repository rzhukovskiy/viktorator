<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class UserController extends BaseController
{
    /** @var $admin AdminEntity  */
    private $admin = null;

    public function init()
    {
        if ($_COOKIE['stoger']) {
            $adminModel = new AdminModel();
            $adminEntity = $adminModel->findBySocialId($_COOKIE['social_id']);

            $this->admin = $adminEntity ? $adminEntity : null;
        }

        parent::init();

        if (!$this->admin || !$this->admin->is_active) {
            header('Location: http://mediastog.ru/site/index');
            exit;
        }
    }

    public function actionCollect()
    {
        $bot = new BotEntity();

        if (!$bot->isActive()) {
            die;
        }

        $userModel = new UserModel();
        $posts = VkSdk::getWallContent('-90206484', $bot->getToken());
        
        foreach ($posts as $post) {
            if ($post['is_pinned']) {
                continue;
            }
            if ($post['date'] < 1489722586) {
                break;
            }
            $listLikes = VkSdk::getLikeList('-90206484', $post['id'], 'post', $bot->getToken());
            foreach ($listLikes as $user_id) {
                $userEntity = $userModel->findBySocialId($user_id);

                VkSdk::isMember('90206484', $user_id);
                if (!$userEntity) {
                    $infoUser = VkSdk::getUserInfoByToken($user_id, $bot->getToken());

                    if (!$infoUser) {
                        continue;
                    }
                    $userEntity = new UserEntity([
                        'social_id' => $user_id,
                        'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                        'scores'    => 0,
                    ]);

                    $userEntity->save();
                }

                $actionEntity = new ActionEntity([
                    'user_id'        => $userEntity->id,
                    'user_social_id' => $userEntity->social_id,
                    'social_id'      => $post['id'],
                    'activity_id'    => 'like',
                ]);
                $actionEntity->save();
            }

            $listComments = VkSdk::getCommentList('-90206484', $post['id'], $bot->getToken());
            foreach ($listComments as $comment) {
                $userEntity = $userModel->findBySocialId($user_id);

                if (!$userEntity) {
                    $infoUser = VkSdk::getUserInfoByToken($user_id, $bot->getToken());
                    if (!$infoUser) {
                        continue;
                    }
                    $userEntity = new UserEntity([
                        'social_id' => $user_id,
                        'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                        'scores'    => 0,
                    ]);

                    $userEntity->save();
                }

                $actionEntity = new ActionEntity([
                    'user_id'        => $userEntity->id,
                    'user_social_id' => $comment['from_id'],
                    'social_id'      => $comment['cid'],
                    'activity_id'    => 'comment',
                    'content'        => 'text',
                ]);
                $actionEntity->save();
            }

            $listRepost = VkSdk::getRepostList('-90206484', $post['id'], $bot->getToken());
            foreach ($listRepost as $user_id) {
                $userEntity = $userModel->findBySocialId($user_id);

                if (!$userEntity) {
                    $infoUser = VkSdk::getUserInfoByToken($user_id, $bot->getToken());
                    if (!$infoUser) {
                        continue;
                    }
                    $userEntity = new UserEntity([
                        'social_id' => $user_id,
                        'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                        'scores'    => 0,
                    ]);

                    $userEntity->save();
                }

                $actionEntity = new ActionEntity([
                    'user_id'        => $userEntity->id,
                    'user_social_id' => $userEntity->social_id,
                    'social_id'      => $post['id'],
                    'activity_id'    => 'repost',
                ]);
                $actionEntity->save();
            }
        }
    }
}