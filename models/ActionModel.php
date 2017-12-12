<?php

/**
 */
class ActionModel extends BaseModel
{
    public static $nameTable = 'action';

    /**
     * @param $activity_id
     * @param $social_id
     * @param $parent_social_id
     * @param $user_id
     * @return bool|ActionEntity
     */
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
            return new ActionEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }        
    }

    /**
     * @param $user_id
     * @return ActionEntity[]|bool
     */
    public static function getByUser($user_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE is_active = :is_active AND user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([
            'is_active' => 1,
            'user_id'   => $user_id,
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

    /**
     * @param $user_id
     * @return array|bool
     */
    public static function getScores($user_id)
    {
        $stmt = self::$pdo
            ->prepare("SELECT description, SUM(scores) as scores FROM " .
                self::$nameTable . ", " . ActivityModel::$nameTable .
                " as activity WHERE is_active = :is_active AND user_id = :user_id AND activity_id = activity.id GROUP BY activity_id");
        $stmt->execute([
            'is_active' => 1,
            'user_id'   => $user_id,
        ]);

        if ($stmt->rowCount()) {
            return $stmt->fetchAll(PDO::FETCH_KEY_PAIR );
        } else {
            return false;
        }
    }

    /**
     * @var int $group_id
     * @return bool
     */
    public static function resetAll($group_id)
    {
        $stmt = self::$pdo
            ->prepare("UPDATE " . self::$nameTable . " SET is_active = 0 WHERE group_id = :group_id");
        return $stmt->execute([
            'group_id'  => $group_id,
        ]);
    }

    /**
     * @var int $group_id
     * @var int $date
     * @return bool
     */
    public static function clearAllAfterDate($group_id, $date)
    {
        $stmt = self::$pdo
            ->prepare("DELETE FROM " . self::$nameTable . " WHERE created_at >= :date AND group_id = :group_id AND activity_id != :activity_id");
        return $stmt->execute([
            'date'        => $date,
            'group_id'    => $group_id,
            'activity_id' => ActivityModel::getByName(ActivityModel::NAME_ALL_LIKE)['id'],
        ]);
    }

    /**
     * @var int $group_id
     * @return array|bool
     */
    public static function getAll($group_id)
    {
        $stmt = self::$pdo
            ->prepare("SELECT action.*, description as activity FROM " . self::$nameTable . " as action, " . ActivityModel::$nameTable .
                " as activity  WHERE is_active = :is_active AND group_id = :group_id AND activity.id = activity_id");
        $stmt->execute([
            'is_active' => 1,
            'group_id'  => $group_id,
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['user_id']][$row['activity']][$row['parent_social_id']][$row['social_id']] = $row;
            }
            return $res;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return ActionEntity|bool
     */
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

    /**
     * @var int $group_id
     * @return ActionEntity|bool
     */
    public static function getLast($group_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([
            'group_id' => $group_id,
        ]);

        if ($stmt->rowCount()) {
            return new ActionEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}