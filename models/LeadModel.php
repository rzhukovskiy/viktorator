<?php

/**
 */
class LeadModel extends BaseModel
{
    public static $nameTable = 'lead';

    /**
     * @param int $id
     * @return bool|LeadEntity
     */
    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            'id' => $id,
        ]);

        if ($stmt->rowCount()) {
            return new LeadEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}