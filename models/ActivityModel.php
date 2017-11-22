<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class ActivityModel extends BaseModel
{
    public static $nameTable = 'activity';

    public static function getByName($name)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE description = :name");
        $stmt->execute([
            'name' => $name,
        ]);

        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } else {
            return false;
        }
    }

    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
        ]);

        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } else {
            return false;
        }
    }

    public static function getAll()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[] = new UserEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }
}