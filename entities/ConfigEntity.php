<?php

/**
 * @property int       $id
 * @property string    $app_id
 * @property string    $app_secret
 * @property string    $redirect_uri
 * @property string    $standalone_id
 */
class ConfigEntity extends BaseEntity
{    
    public function save()
    {
        $id = ConfigModel::save($this->data);

        $this->id = $id;
    }
}