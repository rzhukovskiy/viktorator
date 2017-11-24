<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 10:20
 *
 * @property integer    $id
 * @property string     $type
 * @property string     $content
 * @property string     $response
 * @property integer    $is_active
 * @property integer    $created_at
 */
class ErrorEntity extends BaseEntity
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
        $id = ErrorModel::save($this->data);
        $this->id = $id;
    }
}