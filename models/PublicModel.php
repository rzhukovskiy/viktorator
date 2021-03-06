<?php

/**
 */
class PublicModel extends BaseModel
{
    public static $nameTable = 'public';

    public static function getByAdminId($admin_id)
    {
        $stmt = self::$pdo->prepare(
            "SELECT * FROM " . self::$nameTable . " public, " .
            PublicModel::$nameTable . '_' . AdminModel::$nameTable . "_link " .
            "WHERE group_id = public.id AND admin_id = :admin_id"
        );
        $stmt->execute([
            'admin_id' => $admin_id
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['id']] = new PublicEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    public static function getActiveByAdminId($admin_id)
    {
        $stmt = self::$pdo->prepare(
            "SELECT * FROM " . self::$nameTable . " public, " .
            PublicModel::$nameTable . '_' . AdminModel::$nameTable . "_link " .
            "WHERE group_id = public.id AND admin_id = :admin_id AND token IS NOT NULL"
        );
        $stmt->execute([
            'admin_id' => $admin_id
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['id']] = new PublicEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    public static function getAllActive()
    {
        $stmt = self::$pdo->prepare(
            "SELECT * FROM " . self::$nameTable .
            " WHERE token IS NOT NULL"
        );
        $stmt->execute();

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['id']] = new PublicEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        if ($stmt->rowCount()) {
            return new PublicEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public static function save($data)
    {
        if (isset($data['admin_id'])) {
            $stmt = self::$pdo->prepare("INSERT INTO " . PublicModel::$nameTable . "_" . AdminModel::$nameTable . "_link" .
                " VALUES (:admin_id , :group_id)");
            $stmt->execute([
                'admin_id' => $data['admin_id'],
                'group_id' => $data['id'],
            ]);

            unset($data['admin_id']);
        }

        return parent::save($data);
    }
}