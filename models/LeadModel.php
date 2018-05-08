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

    /**
     * @return LeadEntity[]|bool
     */
    public static function getAll()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[] = new LeadEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }
}