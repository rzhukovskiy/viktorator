<?php

/**
 * @property integer    $id
 * @property integer    $ip
 * @property integer    $created_at
 * @property string     $useragent
 * @property string     $hash
 * @property string     $config
 */
class LeadEntity extends BaseEntity
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
        $id = LeadModel::save($this->data);
        $this->id = $id;
    }
}