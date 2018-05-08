<?php

/**
 */
class FingerprintController extends BaseController
{
    public function actionIndex()
    {
        $this->template = 'login';

        $this->render('fingerprint/index');
    }
}