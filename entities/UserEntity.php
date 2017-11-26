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
        $data['name'] = isset($data['name']) ? substr($data['name'], 0, 255) : '';
        parent::__construct($data);
    }
    
    public function save()
    {
        $id = UserModel::save($this->data);
        $this->id = $id;
    }

    /**
     * @param $date string
     * @return string
     */
    public function saveToTop($date)
    {
        return TopModel::save([
            'social_id' => $this->social_id,
            'group_id'  => $this->group_id,
            'name'      => $this->name,
            'scores'    => $this->scores,
            'date'      => $date,
        ]);
    }
}