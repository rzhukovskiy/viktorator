<?php

/**
 */
class AdminController extends Controller
{
    public function actionList()
    {        
        $this->render('admin/list', [
            'listAdmin' => AdminModel::getAll(),
        ]);
    }

    public function actionActivate()
    {
        $entity = AdminModel::getById($_GET['id']);
        $entity->is_active = $entity->is_active ? 0 : 1;
        $entity->save();

        $this->redirect('admin/list');
    }
}