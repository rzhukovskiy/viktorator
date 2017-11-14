<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class SiteController extends BaseController
{
    public function actionIndex()
    {
        $this->render('auth', [
            'link' => VkSdk::getAuthUrl(),
        ]);
    }

    public function actionAuth()
    {
        $admin = new Admin();
        $admin = $admin->getById(1);
        $this->render('verification');
    }
}