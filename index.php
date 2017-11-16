<?php
require_once(__DIR__ . '/app/Autoloader.php');
spl_autoload_register(array('Autoloader', 'loadPackages'));

Globals::init();
$router = Router::getInstance();
$router->handleRequest();