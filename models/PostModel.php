<?php

/**
 */
class PostModel extends BaseModel
{
    public static $nameTable = 'post';

    /**
     * @param int $group_id
     * @param int $date
     * @return PostEntity[]|bool
     */
    public static function getAllAfterDate($group_id, $date)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE created_at >= :date AND group_id = :group_id");
        $stmt->execute([
            'date'  => $date,
            'group_id'  => $group_id,
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['social_id']] = new PostEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    /**
     * @param int $group_id
     * @param int $date
     * @return bool
     */
    public static function clearAllAfterDate($group_id, $date)
    {
        $stmt = self::$pdo
            ->prepare("DELETE FROM " . self::$nameTable . " WHERE created_at >= :date AND group_id = :group_id");
        return $stmt->execute([
            'created_at'  => $date,
            'group_id'    => $group_id,
        ]);
    }
}