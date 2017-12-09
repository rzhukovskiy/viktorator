<?php

/**
 */
class ActionController extends Controller
{
    public function actionList()
    {
        $this->render('action/list', [
            'listAction' => ActionModel::getByUser($_GET['user_id']),
        ]);
    }

    public function actionDeactivate()
    {
        $actionEntity = ActionModel::getById($_GET['id']);
        $actionEntity->deactivate();

        $this->redirect('action/list?user_id=' . $actionEntity->user_id);
    }
}