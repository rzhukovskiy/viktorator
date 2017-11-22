<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $price
 * @property string     $description
 */
class ActivityEntity extends BaseEntity
{
    public function save()
    {
        $id = ActionModel::save($this->data);
        $this->id = $id;
    }
}