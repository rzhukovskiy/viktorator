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
        parent::__construct($data);
    }
    
    public function save()
    {
        $id = CommentModel::save($this->data);
        $this->id = $id;
    }

    public function delete()
    {
        $postEntity = PostModel::getById($this->post_id);
        
        $listAction = ActionModel::getActivityBySocialAndParent($this->social_id, $postEntity->social_id);
        if ($listAction) {
            foreach ($listAction as $actionEntity) {
                $actionEntity->deactivate();
            }
        }
        
        return CommentModel::delete($this->data);
    }
}