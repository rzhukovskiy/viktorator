<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class SiteController extends BaseController
{
    /** @var $admin AdminEntity  */
    private $admin = null;
    /** @var $admin BotEntity  */
    private $bot   = null;
    
    public function init()
    {
        if ($_COOKIE['stoger']) {
            $adminEntity = AdminModel::findBySocialId($_COOKIE['social_id']);
            
            $this->admin = $adminEntity;
        }

        if(!$this->admin || !$this->admin->is_active) {
            if ($this->action != 'login') {
                $this->redirect('site/login');
            }
        }

        if ($this->admin->is_active) {
            $this->bot = new BotEntity();
        }

        parent::init();
    }

    public function actionIndex()
    {
        if ($this->admin->is_active) {
            $this->render('site/main', [
                'username'   => $this->admin->name,
                'bot'        => $this->bot->isActive(),
                'botname'    => $this->bot->getOwnerName(),
                'lastAction' => ActionModel::getLast(),
            ]);
        } else {
            $this->template = 'login';

            $this->render('site/greet', [
                'username' => $this->admin->name
            ]);
        }
    }

    public function actionLogin()
    {
        $this->template = 'login';

        $code = !empty($_REQUEST['code']) ? $_REQUEST['code'] : null;
        if ($code) {
            $infoToken = VkSdk::getTokenByCode($code);
            if (!$infoToken) {
                exit('Не хочет ВК авторизовать. Как же с ними сложно...');
            }
            $adminEntity = AdminModel::findBySocialId($infoToken['user_id']);

            if (!$adminEntity) {
                $infoUser = VkSdk::getUserInfoByToken($infoToken['user_id'], $infoToken['access_token']);
                $adminEntity = new AdminEntity([
                    'social_id' => $infoUser['uid'],
                    'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                    'token'     => $infoToken['access_token'],
                    'is_active' => 0,
                    'is_bot' => 0,
                ]);
                $adminEntity->save();
            } else {
                $adminEntity->token = $infoToken['access_token'];
                $adminEntity->save();
            }
            $this->admin = $adminEntity;

            setcookie('stoger', 1, null, '/');
            setcookie('social_id', $adminEntity->social_id, null, '/');

            $this->redirect('site/index');
        }

        $this->render('site/auth', [
            'link' => VkSdk::getAuthUrl(),
        ]);
    }

    public function actionConfig()
    {
        if (!empty($_POST['Config'])) {
            $data = [];
            foreach ($_POST['Config'] as $name => $value) {
                $data[] = [
                    'name'  => $name,
                    'value' => $value,
                ];
            }
            $config = new ConfigEntity($data);
            $config->save();

            $this->redirect('site/config');
        }

        $this->render('site/config', [
            'config' => Globals::$config,
        ]);
    }

    public function actionActivity()
    {
        if (!empty($_POST['Activity'])) {
            foreach ($_POST['Activity'] as $data) {
                $activityEntity = new ActivityEntity($data);
                $activityEntity->save();
            }

            $this->redirect('site/activity');
        }

        $this->render('site/activity', [
            'listActivity' => ActivityModel::getAll(),
        ]);
    }
}