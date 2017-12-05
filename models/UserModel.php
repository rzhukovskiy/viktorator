<?php

/**
 */
class UserModel extends BaseModel
{
    public static $nameTable = 'user';
    private static $likes = [];

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
     * @param string $socialId
     * @return bool|UserEntity
     */
    public static function findBySocialId($socialId)
    {
        $stmt = self::$pdo
            ->prepare("SELECT * FROM " . self::$nameTable . " WHERE social_id = :social_id AND group_id = :group_id");
        $stmt->execute([
            'social_id' => $socialId,
            'group_id'  => Globals::$config->group_id,
        ]);

        if ($stmt->rowCount()) {
            return new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    /**
     * @param string $socialId
     * @param string $token
     * @return UserEntity
     */
    public static function createFromSocialId($socialId, $token)
    {
        $stmt = self::$pdo
            ->prepare("SELECT * FROM " . self::$nameTable . " WHERE social_id = :social_id AND group_id = :group_id");
        $stmt->execute([
            'social_id' => $socialId,
            'group_id'  => Globals::$config->group_id,
        ]);

        $userEntity = null;
        if ($stmt->rowCount()) {
            $userEntity = new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $infoUser = VkSdk::getUser($socialId, $token);
            if (!$infoUser) {
                return null;
            }
            $userEntity = new UserEntity([
                'social_id' => $socialId,
                'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                'scores'    => 0,
                'is_active' => 1,
                'is_member' => 0,
                'is_repost' => 0,
            ]);

            if (VkSdk::isMember($userEntity->group_id, $userEntity->social_id)) {
                $userEntity->is_member = 1;
            }

            foreach (self::getLikes($userEntity->group_id, $token) as $user_id) {
                if($user_id == $userEntity->social_id) {
                    $userEntity->is_repost = 1;
                    break;
                }
            }

            $userEntity->save();
        }

        return $userEntity;
    }

    /**
     * @return UserEntity[]|bool
     */
    public static function getAll()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id ORDER BY scores DESC");
        $stmt->execute([
            'group_id'  => Globals::$config->group_id,
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
    
    public static function addScores($social_id, $scores)
    {
        $stmt = self::$pdo->prepare("UPDATE " . self::$nameTable . " SET scores = scores + :scores WHERE group_id = :group_id AND social_id = :social_id");
        $stmt->execute([
            'group_id'  => Globals::$config->group_id,
            'scores'    => $scores,
            'social_id' => $social_id,
        ]);        
    }

    /**
     * @param int $limit
     * @return UserEntity[]|bool
     */
    public static function getTop($limit)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable .
            " WHERE group_id = :group_id AND is_member = 1 ORDER BY scores DESC LIMIT $limit");
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

    private static function getLikes($group_id, $token)
    {
        if (empty(self::$likes)) {
            $offset = 0;
            while (true) {
                $listLike = VkSdk::getLikeWithRepostList(
                    '-' . $group_id,
                    $token,
                    Globals::$config->post_id,
                    'post',
                    $offset
                );

                if(!$listLike) {
                    break;
                }
                self::$likes = array_merge(self::$likes, $listLike);
                $offset += 100;
            }

        }

        return self::$likes;
    }
}