<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class BotController extends BaseController
{
    /** @var $admin AdminEntity  */
    private $admin = null;
    
    public function init()
    {
        if ($_COOKIE['stoger']) {
            $adminEntity = AdminModel::findBySocialId($_COOKIE['social_id']);

            $this->admin = $adminEntity ? $adminEntity : null;
        }

        parent::init();

        if (!$this->admin || !$this->admin->is_active) {
            $this->redirect('site/index');
        }
    }

    public function actionConnect()
    {
        $this->redirect('site/index');
    }
}