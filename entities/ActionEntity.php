<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $group_id
 * @property integer    $social_id
 * @property integer    $parent_social_id
 * @property integer    $user_id
 * @property integer    $user_social_id
 * @property integer    $activity_id
 * @property integer    $scores
 * @property integer    $is_active
 * @property string     $content
 * @property string     $activity
 * @property integer    $created_at
 */
class ActionEntity extends BaseEntity
{
    public function __construct($data)
    {
        if(empty($data['activity_id'])) {
            $row = ActivityModel::getByName($data['activity']);
            $data['activity_id'] = $row['id'];
            $data['scores']      = $row['price'];
        } elseif(empty($data['activity'])) {
            $row = ActivityModel::getById($data['activity_id']);
            $data['activity'] = $row['description'];
        }

        $data['content'] = isset($data['content']) ? substr($data['content'], 0, 255) : '';
        $data['group_id'] = Globals::$config->group_id;
        
        parent::__construct($data);

        if (empty($this->id)) {
            $this->created_at = time();
        }
    }

    public function save()
    {
        if (ActionModel::checkByActivity($this->activity_id, $this->social_id, $this->parent_social_id, $this->user_id)) {
            return;
        }
        
        unset($this->data['activity']);
        $id = ActionModel::save($this->data);

        UserModel::addScores($this->user_social_id, $this->scores);
        
        $this->id = $id;
    }

    public function deactivate()
    {
        $this->is_active = 0;

        unset($this->data['activity']);
        ActionModel::save($this->data);
        UserModel::addScores($this->user_social_id, -1 * $this->scores);
    }
}