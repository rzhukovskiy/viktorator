<?php

/**
 */
class UserController extends Controller
{
    public function actionList()
    {
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : false;
        $listGroup = PublicModel::getActiveByAdminId($this->admin->id);
        if (!$group_id && count($listGroup)) {
            $group_id = array_keys($listGroup)[0];
        }

        $this->render('user/list', [
            'listUser' => UserModel::getAll($group_id),
            'listGroup' => $listGroup,
        ]);
    }

    public function actionTop()
    {
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : false;
        $listGroup = PublicModel::getActiveByAdminId($this->admin->id);
        if (!$group_id && count($listGroup)) {
            $group_id = array_keys($listGroup)[0];
        }

        $this->render('user/list', [
            'listUser' => TopModel::getLast($group_id, 12),
            'listGroup' => $listGroup,
        ]);
    }

    public function actionGive()
    {
        $scores = isset($_REQUEST['scores']) ? $_REQUEST['scores'] : false;
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : false;
        
        if ($scores && $user_id) {
            UserModel::addScores($user_id, $scores);
        }

        $this->redirect('user/list');
    }
}