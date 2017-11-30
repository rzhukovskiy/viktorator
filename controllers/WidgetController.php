<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:38
 */
class WidgetController extends BaseController
{
    public function actionIndex()
    {
        $this->render('widget/index');
    }
}