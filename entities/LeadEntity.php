<?php

/**
 * @property integer    $id
 * @property integer    $ip
 * @property string     $hash
 * @property string     $config
 */
class LeadEntity extends BaseEntity
{


    public function save()
    {
        $id = LeadModel::save($this->data);
        $this->id = $id;
    }
}