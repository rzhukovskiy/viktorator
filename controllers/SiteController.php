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
            $groupEntity = GroupModel::getById($_REQUEST['Group']['id']);
            $groupEntity->token = $code;
            if (!$groupEntity->server_id) {
                $groupEntity->secret = substr(md5(time()), 0, 10);
                $groupEntity->server_id = VkSdk::addCallback(
                    $groupEntity->id,
                    'https://mediastog.ru/callback/vk',
                    'viktorator',
                    $groupEntity->secret,
                    $groupEntity->token
                );
                $groupEntity->confirm = VkSdk::getCallbackCode($groupEntity->id, $groupEntity->token);
                VkSdk::setCallback($groupEntity->id, $groupEntity->server_id, $groupEntity->token);
            }
            $groupEntity->save();

            $this->redirect('group/edit', ['id' => $groupEntity->id]);
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