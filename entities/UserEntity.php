<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $social_id
 * @property string     $name
 * @property integer    $scores
 */
class UserEntity extends BaseEntity
{
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