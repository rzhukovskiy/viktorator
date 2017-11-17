<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 16:44
 */
class ActionModel extends BaseModel
{
    protected $nameTable = 'action';
    
    public function checkByActivity($activity_id, $social_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE activity_id = :activity_id AND social_id = :social_id AND user_id = :user_id");
        $stmt->execute([
            'activity_id' => $activity_id,
            'social_id'   => $social_id,
            'user_id'     => $user_id,
        ]);
        
        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }        
    }

    public function getByUser($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE user_id = :user_id");
        $stmt->execute([
            ':user_id' => $user_id,
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[] = new ActionEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        if ($stmt->rowCount()) {
            return new ActionEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}