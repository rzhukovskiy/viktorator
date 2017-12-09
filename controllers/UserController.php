<?php

/**
 */
class UserController extends Controller
{
    public function actionList()
    {
        $this->render('user/list', [
            'listUser' => UserModel::getAll(),
        ]);
    }

    public function actionTop()
    {
        $this->render('user/top', [
            'listUser' => TopModel::getLast(12),
        ]);
    }
}