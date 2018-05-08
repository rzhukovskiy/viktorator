<?php

/**
 */
class FingerprintController extends Controller
{
    public function actionIndex()
    {
        $this->render('fingerprint/index', [
            'listAction' => ActionModel::getByUser($_GET['user_id']),
        ]);
    }
}