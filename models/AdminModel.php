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

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public function findBySocialId($socialId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE social_id = :social_id");
        $stmt->execute([
            'social_id' => $socialId,
        ]);
        
        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::$nameTable . "");
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

    public function findByBotFlag()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE is_bot = :is_bot");
        $stmt->execute([
            'is_bot' => 1,
        ]);

        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public function connectToBot($id)
    {
        $stmt = $this->pdo->prepare("UPDATE " . self::$nameTable . " SET is_bot = 0");
        $stmt->execute();

        $stmt = $this->pdo->prepare("UPDATE " . self::$nameTable . " SET is_bot = 1 WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
        ]);
    }
}