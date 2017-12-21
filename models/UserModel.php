<?php

/**
 */
class UserModel extends BaseModel
{
    public static $nameTable = 'user';

    /**
     * @var int $group_id
     * @return bool
     */
    public static function resetAll($group_id)
    {
        $stmt = self::$pdo
            ->prepare("UPDATE " . self::$nameTable . " SET scores = 0 WHERE group_id = :group_id");
        return $stmt->execute([
            'group_id'  => $group_id,
        ]);
    }

    /**
     * @param int $socialId
     * @param int $group_id
     * @return bool|UserEntity
     */
    public static function findBySocialId($group_id, $socialId)
    {
        $stmt = self::$pdo
            ->prepare("SELECT * FROM " . self::$nameTable . " WHERE social_id = :social_id AND group_id = :group_id");
        $stmt->execute([
            'social_id' => $socialId,
            'group_id'  => $group_id,
        ]);

        if ($stmt->rowCount()) {
            return new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    /**
     * @param int $social_id
     * @param int $group_id
     * @param string $token
     * @return UserEntity
     */
    public static function createFromSocialId($social_id, $group_id, $token)
    {
        $stmt = self::$pdo
            ->prepare("SELECT * FROM " . self::$nameTable . " WHERE social_id = :social_id AND group_id = :group_id");
        $stmt->execute([
            'social_id' => $social_id,
            'group_id'  => $group_id,
        ]);

        $userEntity = null;
        if ($stmt->rowCount()) {
            $userEntity = new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $infoUser = VkSdk::getUser($social_id, $token);
            if (!$infoUser) {
                return null;
            }
            $userEntity = new UserEntity([
                'social_id' => $social_id,
                'group_id'  => $group_id,
                'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                'scores'    => 0,
                'is_active' => 1,
                'is_member' => 0,
                'is_repost' => 0,
            ]);

            if (VkSdk::isMember($userEntity->group_id, $userEntity->social_id)) {
                $userEntity->is_member = 1;
            }

            $userEntity->save();
        }

        return $userEntity;
    }

    /**
     * @param int $group_id
     * @return UserEntity[]|bool
     */
    public static function getAll($group_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id ORDER BY scores DESC");
        $stmt->execute([
            'group_id'  => $group_id,
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['social_id']] = new UserEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }

    /**
     * @param int $group_id
     * @return UserEntity[]|bool
     */
    public static function getAllWithScores($group_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id AND scores != 0 ORDER BY scores DESC");
        $stmt->execute([
            'group_id'  => $group_id,
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['social_id']] = new UserEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }
    
    public static function addScores($id, $scores)
    {
        $stmt = self::$pdo->prepare("UPDATE " . self::$nameTable . " SET scores = scores + :scores WHERE id = :id");
        $stmt->execute([
            'scores' => $scores,
            'id'     => $id,
        ]);        
    }

    /**
     * @param int $limit
     * @param int $group_id
     * @return UserEntity[]|bool
     */
    public static function getTop($group_id, $limit)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE group_id = :group_id AND is_member = 1 ORDER BY scores DESC LIMIT $limit");
        $stmt->execute([
            'group_id'  => $group_id,
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