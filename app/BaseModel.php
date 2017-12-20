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
        $dsn = 'mysql:dbname=mediastog;host=127.0.0.1';
        $user = 'mediastog';
        $password = 'aWEjOv+k;d$m16W';

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

    /**
     * @param $params
     * @return string
     */
    public static function save($params)
    {
        $columns = implode("`, `", array_keys($params));
        $values  = implode(", :", array_keys($params));

        $updates = [];
        foreach ($params as $name => $value) {
            $params[$name . '1'] = $value;
            $updates[] = "`$name` = :{$name}1";
        }
        $updates = implode(", ", $updates);

        $stmt = self::$pdo->prepare("INSERT INTO " . static::$nameTable . " (`$columns`) VALUES (:$values)" .
            " ON DUPLICATE KEY UPDATE $updates");
        $stmt->execute($params);

        if (empty($params['id'])) {
            return self::$pdo->lastInsertId();
        } else {
            return $params['id'];
        }
    }

    /**
     * @param $params
     * @return string
     */
    public static function delete($params)
    {
        $updates = [];
        foreach ($params as $name => $value) {
            $updates[] = "`$name` = :$name";
        }
        $updates = implode(", ", $updates);

        if (empty($params['id'])) {
            $stmt = self::$pdo->prepare("DELETE FROM " . static::$nameTable . " WHERE $updates");
            return $stmt->execute($params);            
        } else {
            $stmt = self::$pdo->prepare("DELETE FROM " . static::$nameTable . " WHERE id = :id");
            return $stmt->execute([
                'id' => $params['id'],
            ]);            
        }
    }
}