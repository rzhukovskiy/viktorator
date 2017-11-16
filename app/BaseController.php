<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:06
 */
class BaseController
{
    protected $action = null;
    
    public function __construct($action)
    {
        $this->action = $action;
        $this->init();
    }
    
    public function init() {

    }

    public function redirect($destination)
    {
        $url = 'Location: http://mediastog.ru/' . ltrim($destination, '/');
        header($url);
        exit;
    }

    public function render($view, $params = null)
    {
        if (is_array($params)) {
            extract($params);
        }
        require 'views/' . $view . '.php';
    }
}