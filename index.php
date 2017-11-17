<?php
if (!empty($argv)) {
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
}

require_once(__DIR__ . '/app/Autoloader.php');
spl_autoload_register(array('Autoloader', 'loadPackages'));

Globals::init();
$router = Router::getInstance();
$router->handleRequest();