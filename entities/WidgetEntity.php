<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $group_id
 * @property integer    $group_social_id
 * @property integer    $user_social_id
 * @property string     $title
 * @property string     $text
 * @property string     $main_text
 * @property string     $button_text
 * @property string     $button_url
 */
class WidgetEntity extends BaseEntity
{
    public function save()
    {
        $id = WidgetModel::save($this->data);
        $this->id = $id;
    }
}