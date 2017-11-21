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
 * @property string     $name
 * @property integer    $scores
 * @property integer    $is_active
 * @property integer    $is_repost
 * @property integer    $is_member
 */
class UserEntity extends BaseEntity
{
    public function __construct($data)
    {
        $data['group_id'] = Globals::$config->group_id;
        parent::__construct($data);
    }

    public function addScores($amount)
    {
        $this->scores = max(0, $this->scores + $amount);
        $this->save();
    }
    
    public function save()
    {
        $model = new UserModel();
        $id = $model->save($this->data);
        $this->id = $id;
    }
}