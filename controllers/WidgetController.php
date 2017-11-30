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
        $group_social_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : null;
        
        $this->template = 'widget';
        $this->render('widget/index', [
            'widgetEntity' => WidgetModel::getByGroupSocialId($group_social_id),
            'group_social_id' => $group_social_id,
        ]);
    }
    public function actionSave()
    {
        if (!empty($_POST)) {
            WidgetModel::save($_POST);
        }
    }
}