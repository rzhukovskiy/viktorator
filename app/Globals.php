<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 16.11.2017
 * Time: 15:52
 */
class Globals
{
    /** @var  $config ConfigEntity */
    public static $config;

    /**
     * @return Router
     */
    public static function init()
    {
        $model = new ConfigModel();
        static::$config = $model->getAll();
    }

}