<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class TopModel extends BaseModel
{
    public static $nameTable = 'top';

    public static function getLast($limit)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE group_id = :group_id ORDER BY date, scores DESC LIMIT $limit");
        $stmt->execute([
            'group_id'  => Globals::$config->group_id,
        ]);

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