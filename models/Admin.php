<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 *
 * @property integer    $isAdmin
 * @property string     $name
 * @property string     $token
 */
class Admin extends BaseModel
{
    protected $nameTable = 'admin';

    public function createAdmin($name, $token)
    {

    }
}