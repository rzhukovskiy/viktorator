<?php

/**
 * @property integer    $id
 * @property string     $name
 * @property string     $picture
 * @property string     $slug
 * @property string     $secret
 * @property string     $confirm
 * @property string     $token
 * @property string     $standalone_token
 * @property integer    $topic_id
 * @property integer    $post_id
 */
class GroupEntity extends BaseEntity
{
    public function save()
    {        
        $this->id = GroupModel::save($this->data);
    }

    public function isActive()
    {
        return !empty($this->token);
    }
}