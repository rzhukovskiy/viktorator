<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class ActivityModel extends BaseModel
{
    protected $nameTable = 'activity';

    public function getIdByName($name)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE description = :name");
        $stmt->execute([
            'name' => $name,
        ]);

        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['id'];
        } else {
            return false;
        }
    }
}