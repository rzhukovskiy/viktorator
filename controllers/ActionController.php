<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class ActionController extends BaseController
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