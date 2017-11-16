<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $social_id
 * @property integer    $user_id
 * @property integer    $user_social_id
 * @property integer    $activity_id
 * @property string     $content
 */
class ActionEntity extends BaseEntity
{
    public function __construct($data)
    {
        $activityModel = new ActivityModel();
        $data['activity_id'] = $activityModel->getIdByName($data['activity_id']);
        
        parent::__construct($data);
    }

    public function save()
    {
        $model = new ActionModel();
        if ($model->checkByActivity($this->activity_id, $this->social_id, $this->user_id)) {
            return;
        }
        
        $id = $model->save($this->data);
        $this->id = $id;
    }
}