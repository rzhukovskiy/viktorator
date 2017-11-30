<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 16:44
 */
class WidgetModel extends BaseModel
{
    public static $nameTable = 'widget';

    /**
     * @param $group_social_id
     * @return WidgetEntity|bool
     */
    public static function getByGroupSocialId($group_social_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE group_social_id = :group_social_id");
        $stmt->execute([
            'group_social_id'  => $group_social_id,
        ]);

        if ($stmt->rowCount()) {
            return new WidgetEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}