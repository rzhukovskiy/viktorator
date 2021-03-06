<?php

/**
 */
class CommentModel extends BaseModel
{
    public static $nameTable = 'comment';

    /**
     * @param int $post_id
     * @return CommentEntity[]|bool
     */
    public static function getAllByPost($post_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE post_id = :post_id");
        $stmt->execute([
            'post_id' => $post_id,
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
     * @param int $group_id
     * @param int $social_id
     * @return CommentEntity|bool
     */
    public static function getByGroupAndSocialId($group_id, $social_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id AND social_id = :social_id");
        $stmt->execute([
            'group_id'  => $group_id,
            'social_id' => $social_id,
        ]);

        if ($stmt->rowCount()) {
            return new CommentEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    /**
     * @param int $group_id
     * @return bool
     */
    public static function clearAllEmpty($group_id)
    {
        $stmt = self::$pdo
            ->prepare("DELETE FROM " . self::$nameTable .
                " WHERE group_id = :group_id AND post_id NOT IN (SELECT id FROM " . PostModel::$nameTable . ")");
        return $stmt->execute([
            'group_id' => $group_id,
        ]);
    }
}