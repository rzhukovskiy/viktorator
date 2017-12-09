<?php

/**
 */
class ErrorController extends Controller
{
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