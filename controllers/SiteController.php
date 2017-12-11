<?php

/**
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        if ($this->admin->is_active) {
            $this->render('site/main', [
                'username'   => $this->admin->name,
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

        if (!empty($_REQUEST['Group'])) {
            $publicEntity = PublicModel::getById($_REQUEST['Group']['id']);
            $infoToken = VkSdk::getTokenByCode($code, 'Group[id]=' . $publicEntity->id);
            if (!$infoToken) {
                exit('Не хочет ВК авторизовать. Как же с ними сложно...');
            }
            $publicEntity->token = $infoToken['access_token_' . $publicEntity->id];
            if (!$publicEntity->server_id) {
                $publicEntity->secret = substr(md5(time()), 0, 10);
                $publicEntity->server_id = VkSdk::addCallback(
                    $publicEntity->id,
                    'https://mediastog.ru/callback/vk',
                    'viktorator',
                    $publicEntity->secret,
                    $publicEntity->token
                );
                $publicEntity->confirm = VkSdk::getCallbackCode($publicEntity->id, $publicEntity->token);
                VkSdk::setCallback($publicEntity->id, $publicEntity->server_id, $publicEntity->token);
            }
            $publicEntity->save();

            $this->redirect('group/edit', ['id' => $publicEntity->id]);
        }

        if ($code) {
            $infoToken = VkSdk::getTokenByCode($code);
            if (!$infoToken) {
                exit('Не хочет ВК авторизовать. Как же с ними сложно...');
            }
            $adminEntity = AdminModel::findBySocialId($infoToken['user_id']);

            if (!$adminEntity) {
                $infoUser = VkSdk::getUser($infoToken['user_id'], $infoToken['access_token']);
                $adminEntity = new AdminEntity([
                    'social_id' => $infoUser['id'],
                    'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                    'token'     => $infoToken['access_token'],
                    'is_active' => 1,
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
            $config = new ConfigEntity($_POST['Config']);
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