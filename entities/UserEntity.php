<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property integer    $group_id
 * @property integer    $social_id
 * @property string     $name
 * @property integer    $scores
 * @property integer    $is_active
 * @property integer    $is_repost
 * @property integer    $is_member
 */
class UserEntity extends BaseEntity
{
    public function __construct($data)
    {
        $data['group_id'] = Globals::$config->group_id;
        parent::__construct($data);

        if (!$this->is_member && VkSdk::isMember($this->group_id, $this->social_id)) {
            $this->is_member = 1;
            $this->save();
        }

        $offset = 0;
        if (!$this->is_repost) {
            $listRepost = VkSdk::getRepostList('-' . $this->group_id,
                Globals::$config->standalone_token,
                Globals::$config->post_id,
                $offset);

            foreach ($listRepost as $repost) {
                if($repost['from_id'] == $this->social_id) {
                    $this->is_repost = 1;
                    $this->save();
                    break;
                }
            }
        }
    }

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