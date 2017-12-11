<?php

/**
 * @property integer    $id
 * @property integer    $group_id
 * @property integer    $social_id
 * @property integer    $likes
 * @property integer    $comments
 * @property integer    $reposts
 * @property integer    $created_at
 */
class PostEntity extends BaseEntity
{
    public function __construct($data)
    {
        parent::__construct($data);

        if (!$this->id) {
            $this->created_at = time();
        }
    }
    
    public function save()
    {
        $id = PostModel::save($this->data);
        $this->id = $id;
    }
}