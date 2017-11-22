<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:14
 */
class BaseModel
{
    /** @var  $pdo PDO */
    protected static $pdo;
    protected static $nameTable;

    public static function init()
    {
        $dsn = 'mysql:dbname=viktorator;host=127.0.0.1';
        $user = 'root';
        $password = 'vBghJk';

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$pdo = new PDO($dsn, $user, $password, $opt);
    }

    private function __construct()
    {
    }

    public static function save($params)
    {
        if (empty($params['id'])) {
            $columns = implode("`, `", array_keys($params));
            $values  = implode("', '", array_values($params));

            $stmt = self::$pdo->prepare("INSERT INTO " . static::$nameTable . " (`$columns`) VALUES ('$values')");
            $stmt->execute();

            return self::$pdo->lastInsertId();
        } else {
            $values = [];
            foreach ($params as $name => $value) {
                $values[] = "`$name` = '$value'";
            }
            $values = implode(", ", $values);
            $stmt = self::$pdo->prepare("UPDATE " . static::$nameTable . " SET $values WHERE id = :id");
            $stmt->execute([
                ':id' => $params['id'],
            ]);

            return $params['id'];
        }
    }
}