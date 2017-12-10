<?php

/**
 */
class WidgetController extends BaseController
{
    public function actionIndex()
    {
        $group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : null;

        if (!$group_id) {
            exit('Для запуска из групп');
        }
        
        $this->template = 'widget';
        $this->render('widget/index', [
            'widgetEntity' => WidgetModel::getByGroupId($group_id),
            'group_id' => $group_id,
        ]);
    }
    public function actionSave()
    {
        if (!empty($_POST)) {
            WidgetModel::save($_POST);
        }
    }
}