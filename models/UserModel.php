<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class UserModel extends BaseModel
{
    protected $nameTable = 'user';

    public function findBySocialId($socialId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE social_id = :social_id");
        $stmt->execute([
            'social_id' => $socialId,
        ]);

        if ($stmt->rowCount()) {
            return new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}