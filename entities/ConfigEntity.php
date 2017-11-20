<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property string    $group_id
 * @property string    $group_secret
 * @property string    $group_confirm
 * @property string    $topic_id
 * @property string    $app_id
 * @property string    $app_secret
 * @property string    $redirect_uri
 * @property string    $standalone_id
 * @property string    $standalone_token
 */
class ConfigEntity extends BaseEntity
{
    public function __construct($data)
    {
        $newdata = [];
        foreach ($data as $row) {
            $newdata[$row['name']] = $row['value'];
        }
        
        parent::__construct($newdata);
    }
    
    public function save()
    {
        $model = new ConfigModel();
        $model->clearAll();
        foreach ($this->data as $name => $value) {
            $model->save([
                'name'  => $name,
                'value' => $value,
            ]);
        }
    }
}