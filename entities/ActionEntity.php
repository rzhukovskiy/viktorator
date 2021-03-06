<?php

/**
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
            $data['scores']      = !empty($data['scores']) ? $data['scores'] : $row['price'];
        } elseif(empty($data['activity'])) {
            $row = ActivityModel::getById($data['activity_id']);
            $data['activity'] = $row['description'];
        }

        $data['content'] = isset($data['content']) ? substr($data['content'], 0, 255) : '';
        
        parent::__construct($data);

        if (!$this->id) {
            $this->created_at = time();
        }
    }

    public function save()
    {
        if (ActionModel::checkByActivity($this->activity_id, $this->social_id, $this->parent_social_id, $this->user_id)) {
            if (!$this->is_active) {
                UserModel::addScores($this->user_id, -1 * $this->scores);
            }            
        }
        
        unset($this->data['activity']);
        $id = ActionModel::save($this->data);

        UserModel::addScores($this->user_id, $this->scores);
        
        $this->id = $id;
    }

    public function delete()
    {
        if ($this->is_active) {
            UserModel::addScores($this->user_id, -1 * $this->scores);
        }

        return ActionModel::delete($this->data);
    }

    public function deactivate()
    {
        if (!$this->is_active) {
            return;
        }
        
        $this->is_active = 0;

        unset($this->data['activity']);
        ActionModel::save($this->data);
        UserModel::addScores($this->user_id, -1 * $this->scores);
    }

    public function activate()
    {
        if ($this->is_active) {
            return;
        }
        
        $this->is_active = 1;

        unset($this->data['activity']);
        ActionModel::save($this->data);
        UserModel::addScores($this->user_id, $this->scores);
    }
}