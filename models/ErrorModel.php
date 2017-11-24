<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class ErrorModel extends BaseModel
{
    public static $nameTable = 'error';

    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        if ($stmt->rowCount()) {
            return new ErrorEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public static function getAll()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " ORDER BY created_at DESC");
        $stmt->execute();

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[] = new ErrorEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    public static function getCaptchaError()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE content LIKE '%Captcha needed%' ORDER BY created_at DESC");
        $stmt->execute();

        if ($stmt->rowCount()) {
            return new ErrorEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}