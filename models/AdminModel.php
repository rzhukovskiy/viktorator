<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class AdminModel extends BaseModel
{
    protected $nameTable = 'admin';

    public function findBySocialId($socialId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE social_id = :social_id");
        $stmt->execute([
            'social_id' => $socialId,
        ]);
        
        if ($stmt->rowCount()) {
            return new AdminEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    public function findByBotFlag()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE is_bot = :is_bot");
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
        $stmt = $this->pdo->prepare("UPDATE $this->nameTable SET is_bot = 0");
        $stmt->execute();

        $stmt = $this->pdo->prepare("UPDATE $this->nameTable SET is_bot = 1 WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
        ]);
    }
}