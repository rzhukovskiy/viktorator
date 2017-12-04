<?php

/**
 */
class CommentModel extends BaseModel
{
    public static $nameTable = 'post';

    /**
     * @param int $post_id
     * @return CommentEntity[]|bool
     */
    public static function getAllByPost($post_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE post_id = :post_id");
        $stmt->execute([
            'post_id'  => $post_id,
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['social_id']] = new CommentEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    /**
     * @param int $date
     * @param int $group_id
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