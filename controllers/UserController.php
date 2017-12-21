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
            'listUser' => UserModel::getAllWithScores($group_id),
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
            $userEntity = UserModel::getById($user_id);
            $actionEntity = new ActionEntity([
                'group_id'         => $userEntity->group_id,
                'user_id'          => $userEntity->id,
                'user_social_id'   => $userEntity->social_id,
                'social_id'        => time(),
                'parent_social_id' => time(),
                'scores'           => $scores,
                'activity'         => ActivityModel::NAME_ADMIN,
            ]);
            $actionEntity->save();
        }

        $this->redirect('user/list');
    }
}