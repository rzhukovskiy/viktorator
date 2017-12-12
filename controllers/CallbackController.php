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
        
        if (!isset($data->group_id) || !isset($data->type)) {
            $this->exitOk();
        }
        
        $publicEntity = PublicModel::getById($data->group_id);
        if (!$publicEntity || $data->secret != $publicEntity->secret) {
            $this->exitOk();
        }

        $message = null;
        switch ($data->type) {
            case 'board_post_new':
                CallbackModel::newTopicComment($publicEntity, $data);
                break;
            case 'wall_post_new':
                CallbackModel::newPost($publicEntity, $data);
                break;
            case 'wall_repost':
                CallbackModel::newRepost($publicEntity, $data);
                break;
            case 'wall_reply_new':
                CallbackModel::newPostComment($publicEntity,
                    $data);
                break;
            case 'wall_reply_delete':
                CallbackModel::removePostComment($data);
                break;
            case 'wall_reply_restore':
                CallbackModel::restorePostComment($data);
                break;
            case 'confirmation':
                $message = $publicEntity->confirm;
                break;
        }
        
        $this->exitOk($message);
    }

    private function exitOk($message = null)
    {
        echo $message ? $message : 'ok';
        exit();
    }
}