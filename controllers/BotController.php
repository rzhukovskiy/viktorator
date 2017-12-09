<?php

/**
 */
class BotController extends Controller
{
    public function actionConnect()
    {
        $this->admin->connectToBot();
        $this->redirect('site/index');
    }
}