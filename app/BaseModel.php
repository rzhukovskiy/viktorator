<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:14
 */
class BaseModel
{
    public $pdo;

    public function __construct()
    {
        $dsn = 'mysql:dbname=viktorator;host=127.0.0.1';
        $user = 'root';
        $password = 'vBghJk';

        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }
}