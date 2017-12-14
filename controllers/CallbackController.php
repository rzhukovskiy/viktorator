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
        list($startDate, $endDate) = $this->getWeekPeriod();
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
            case 'group_join':
                CallbackModel::newUser($publicEntity, $data);
                break;
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
                CallbackModel::newPostComment($publicEntity, $data, $startDate, $endDate);
                break;
            case 'wall_reply_delete':
                CallbackModel::removePostComment($publicEntity, $data, $startDate, $endDate);
                break;
            case 'wall_reply_restore':
                CallbackModel::restorePostComment($publicEntity, $data, $startDate, $endDate);
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

    /**
     * @return array
     */
    private function getWeekPeriod()
    {
        //Moscow gmt +3
        $startDate = new DateTime();
        $startDate->setTimestamp(strtotime('this week'))->setTime(0, 0, 0);
        $startDate = $startDate->getTimestamp() - 3 * 3600;
        if ((7 * 24 * 3600 - time() + $startDate) <= 0) {
            $startDate = new DateTime();
            $startDate->setTimestamp(strtotime('next week'))->setTime(0, 0, 0);
            $startDate = $startDate->getTimestamp() - 3 * 3600;

            $endDate = new DateTime();
            $endDate->setTimestamp(strtotime('next sunday'))->setTime(20, 59, 59);
            $endDate = $endDate->getTimestamp();
        } else {
            $endDate = new DateTime();
            $endDate->setTimestamp(time())->setTime(20, 59, 59);
            $endDate = $endDate->getTimestamp();
        }

        return [$startDate, $endDate];
    }
}