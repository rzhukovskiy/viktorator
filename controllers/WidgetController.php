<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class WidgetController extends BaseController
{
    /** @var $admin AdminEntity  */
    private $admin = null;

    public function init()
    {
        if ($_COOKIE['stoger']) {
            $adminEntity = AdminModel::findBySocialId($_COOKIE['social_id']);

            $this->admin = $adminEntity;
        }

        parent::init();
    }

    public function actionIndex()
    {
        if (!$this->admin) {
            $this->template = 'widget';
        }
        $this->render('widget/index');
    }
}