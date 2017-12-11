<?php
error_reporting(E_ALL);
if (!empty($argv)) {
    defined('ENV') or define('ENV', 'console');
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
} else {
    defined('ENV') or define('ENV', 'http');
}

require_once(__DIR__ . '/app/Autoloader.php');
spl_autoload_register(array('Autoloader', 'loadPackages'));

try {
    BaseModel::init();
    Globals::init();
    $router = Router::getInstance();
    $router->handleRequest();
} catch(Exception $ex) {
    print_r($ex);die;
}