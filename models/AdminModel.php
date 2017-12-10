<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class AdminModel extends BaseModel
{
    public static $nameTable = 'admin';

    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public static function findBySocialId($socialId)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE social_id = :social_id");
        $stmt->execute([
            'social_id' => $socialId,
        ]);
        
        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public static function getAll()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . "");
        $stmt->execute();

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[] = new AdminEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    public static function getByGroupId($group_id)
    {
        $stmt = self::$pdo->prepare(
            "SELECT * FROM " . self::$nameTable . " admin, " . GroupModel::$nameTable . AdminModel::$nameTable . "_link " .
            "WHERE group_id = :group_id AND admin_id = admin.id"
        );
        $stmt->execute([
            'group_id' => $group_id
        ]);

        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}