<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class ErrorController extends BaseController
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
        if (!empty($_POST['Error'])) {
            $errorEntity = ErrorModel::getById($_POST['Error']['id']);
            $errorEntity->response = $_POST['Error']['response'];
            $errorEntity->save();

            $this->redirect('error/list');
        }
        
        $this->render('error/list', [
            'captchaError' => ErrorModel::getCaptchaError(),
            'listError' => ErrorModel::getAll(),
        ]);
    }

    public function actionClear()
    {
        ErrorModel::clearAll();
        $this->redirect('error/list');
    }
}