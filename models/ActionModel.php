<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 15.11.2017
 * Time: 16:44
 */
class ActionModel extends BaseModel
{
    public static $nameTable = 'action';
    
    public static function checkByActivity($activity_id, $social_id, $parent_social_id, $user_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE activity_id = :activity_id AND social_id = :social_id AND parent_social_id = :parent_social_id AND user_id = :user_id");
        $stmt->execute([
            'activity_id'       => $activity_id,
            'social_id'         => $social_id,
            'parent_social_id'  => $parent_social_id,
            'user_id'           => $user_id,
        ]);
        
        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }        
    }

    public static function getByUser($user_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE user_id = :user_id AND group_id = :group_id");
        $stmt->execute([
            ':user_id' => $user_id,
            'group_id' => Globals::$config->group_id,
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

    public static function getScores($user_id)
    {
        $stmt = self::$pdo
            ->prepare("SELECT description, SUM(scores) as scores FROM " .
                self::$nameTable . ", " . ActivityModel::$nameTable .
                " as activity WHERE user_id = :user_id AND activity_id = activity.id AND group_id = :group_id GROUP BY activity_id");
        $stmt->execute([
            ':user_id' => $user_id,
            'group_id'  => Globals::$config->group_id,
        ]);

        if ($stmt->rowCount()) {
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR );
        } else {
            return false;
        }
    }

    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
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