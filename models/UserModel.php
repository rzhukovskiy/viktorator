<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:19
 */
class UserModel extends BaseModel
{
    protected $nameTable = 'user';

    /**
     * @param string $socialId
     * @return bool|UserEntity
     */
    public function findBySocialId($socialId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE social_id = :social_id");
        $stmt->execute([
            'social_id' => $socialId,
        ]);

        if ($stmt->rowCount()) {
            return new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }

    /**
     * @param string $socialId
     * @return UserEntity
     */
    public function createFromSocialId($socialId)
    {
        $stmt = $this->pdo
            ->prepare("SELECT * FROM $this->nameTable WHERE social_id = :social_id AND group_id = :group_id");
        $stmt->execute([
            'social_id' => $socialId,
            'group_id'  => Globals::$config->group_id,
        ]);

        $userEntity = null;
        if ($stmt->rowCount()) {
            $userEntity = new UserEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            if (!VkSdk::isMember(Globals::$config->group_id, $socialId)) {
                return null;
            }
            $infoUser = VkSdk::getUser($socialId, self::$token);

            if (!$infoUser) {
                return null;
            }
            $userEntity = new UserEntity([
                'social_id' => $socialId,
                'name'      => $infoUser['first_name'] . ' ' . $infoUser['last_name'],
                'scores'    => 0,
            ]);

            $userEntity->save();
        }

        return $userEntity;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->nameTable WHERE group_id = :group_id");
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