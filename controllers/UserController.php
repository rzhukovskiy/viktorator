<?php

/**
 */
class UserController extends Controller
{
    public function actionList()
    {
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : false;
        $listGroup = GroupModel::getActiveByAdminId($this->admin->id);
        if (!$group_id && count($listGroup)) {
            $group_id = $listGroup[0]->id;
        }

        $this->render('user/list', [
            'listUser' => UserModel::getAll($group_id),
            'listGroup' => $listGroup,
        ]);
    }

    public function actionTop()
    {
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : false;
        $listGroup = GroupModel::getActiveByAdminId($this->admin->id);
        if (!$group_id && count($listGroup)) {
            $group_id = $listGroup[0]->id;
        }

        $this->render('user/list', [
            'listUser' => TopModel::getLast($group_id, 12),
            'listGroup' => $listGroup,
        ]);
    }
}