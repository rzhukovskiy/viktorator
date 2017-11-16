<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 15:11
 */
class Router
{
    private $defaultController = 'site';
    private $defaultAction = 'index';
    private static $instance;

    private function __construct()
    {

    }

    /**
     * @return Router
     */
    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function handleRequest()
    {
        list($uri, $query) = explode('?', $_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = $query;
        list($controller, $action) = explode('/', trim($uri, '/'));
        if (empty($controller)) {
            $controller = $this->defaultController;
        }
        if (empty($action)) {
            $action = $this->defaultAction;
        }

        $controller = ucfirst($controller) . 'Controller';
        $action     = 'action' . ucfirst($action);

        try {
            /** @var BaseController $controllerObject */
            $controllerObject = new $controller($action);
            $controllerObject->$action();
        } catch (Exception $e) {
            http_response_code(404);
            die();
        }
    }
}