<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:06
 */
class BaseController
{
    public function render($view, $params = null)
    {
        if (is_array($params)) {
            extract($params);
        }
        require 'views/' . $view . '.php';
    }
}