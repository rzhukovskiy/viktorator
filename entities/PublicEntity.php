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
 * @property integer    $server_id
 */
class PublicEntity extends BaseEntity
{
    public function save()
    {        
        $this->id = PublicModel::save($this->data);
    }

    public function isActive()
    {
        return (bool)$this->token;
    }

    public function getAdmin()
    {
        return AdminModel::getByGroupId($this->id);
    }
}