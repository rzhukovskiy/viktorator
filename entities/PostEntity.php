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

    public function delete()
    {
        foreach (ActionModel::getActivityBySocialAndParent($this->social_id, $this->social_id) as $actionEntity) {
            $actionEntity->deactivate();
        }
        
        foreach (CommentModel::getAllByPost($this->id) as $commentEntity) {
            $commentEntity->delete();
        }

        return parent::delete();
    }
}