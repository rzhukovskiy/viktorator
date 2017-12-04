<?php

/**
 * @property integer    $id
 * @property integer    $post_id
 * @property integer    $group_id
 * @property integer    $social_id
 * @property integer    $likes
 * @property integer    $created_at
 */
class CommentEntity extends BaseEntity
{
    public function __construct($data)
    {
        $data['group_id'] = Globals::$config->group_id;
        parent::__construct($data);

        if (!$this->id) {
            $this->created_at = time();
        }
    }
    
    public function save()
    {
        $id = CommentModel::save($this->data);
        $this->id = $id;
    }
}