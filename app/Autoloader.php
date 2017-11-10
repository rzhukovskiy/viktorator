<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 17:00
 */
class Autoloader
{
    private static $listPath = [
        'app/',
        'controllers/',
        'models/',
        'lib/',
    ];

    private static $lastLoadedFilename;

    public static function loadPackages($className)
    {
        $pathParts = explode('_', $className);
        foreach (self::$listPath as $path) {
            self::$lastLoadedFilename = $path . implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';
            if (file_exists(self::$lastLoadedFilename)) {
                require_once(self::$lastLoadedFilename);
            }
        }
    }
}