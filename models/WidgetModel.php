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
     * @param $social_group_id
     * @return WidgetEntity|bool
     */
    public static function getByGroupSocialId($social_group_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE social_group_id = :social_group_id");
        $stmt->execute([
            'social_group_id'  => $social_group_id,
        ]);

        if ($stmt->rowCount()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
}