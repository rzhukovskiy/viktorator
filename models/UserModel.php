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

    /**
     * @param string $socialId
     * @return bool|UserEntity
     */
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

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable");
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