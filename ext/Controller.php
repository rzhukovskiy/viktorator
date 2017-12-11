<?php

/**
 */
class Controller extends BaseController
{
    /** @var $admin AdminEntity  */
    protected $admin = null;
    /** @var $admin BotEntity  */
    protected $bot   = null;

    public function init()
    {
        if ($_COOKIE['stoger']) {
            $adminEntity = AdminModel::findBySocialId($_COOKIE['social_id']);

            $this->admin = $adminEntity ? $adminEntity : null;
        }

        if ($this->controller . '/'. $this->action != 'site/login' && (!$this->admin || !$this->admin->is_active)) {
            $this->redirect('site/login');
        }

        parent::init();
    }
}