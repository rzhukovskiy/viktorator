<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class AdminController extends BaseController
{
    /** @var $admin AdminEntity  */
    private $admin = null;
    /** @var $admin BotEntity  */
    private $bot   = null;

    public function init()
    {
        if ($_COOKIE['stoger']) {
            $adminEntity = AdminModel::findBySocialId($_COOKIE['social_id']);

            $this->admin = $adminEntity ? $adminEntity : null;
        }

        if (!$this->admin || !$this->admin->is_active) {
            $this->redirect('site/login');
        }

        if ($this->admin->is_active) {
            $this->bot = new BotEntity();
        }

        parent::init();
    }

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