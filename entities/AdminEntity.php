<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $social_id
 * @property integer    $is_active
 * @property string     $name
 * @property string     $token
 */
class AdminEntity extends BaseEntity
{
    public function save()
    {
        $id = AdminModel::save($this->data);
        $this->id = $id;
    } 
}