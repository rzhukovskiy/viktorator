<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class UserModel extends BaseModel
{
    public static $nameTable = 'user';

    /**
     * @param string $socialId
     * @return bool|UserEntity
     */
    public function findBySocialId($socialId)
    {
        $stmt = $this->pdo
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
    public function createFromSocialId($socialId, $token)
    {
        $stmt = $this->pdo
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
            $offset = 0;
            $listRepost = VkSdk::getRepostList('-' . $userEntity->group_id,
                Globals::$config->standalone_token,
                Globals::$config->post_id,
                $offset);

            foreach ($listRepost as $repost) {
                if($repost['from_id'] == $userEntity->social_id) {
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
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id");
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

    /**
     * @param int $limit
     * @return UserEntity[]|bool
     */
    public function getTop($limit)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE group_id = :group_id ORDER BY scores DESC LIMIT $limit");
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