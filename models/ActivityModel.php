<?php

/**
 */
class ActivityModel extends BaseModel
{
    const NAME_LIKE         = 'like';
    const NAME_COMMENT      = 'comment';
    const NAME_FIRST_LIKE   = 'first_like';
    const NAME_TEN_LIKE     = 'ten_like';
    const NAME_COMMENT_LIKE = 'comment_like';
    const NAME_AUTHOR_LIKE  = 'author_like';
    const NAME_POST_LIKE    = 'post_like';
    const NAME_ALL_LIKE     = 'all_like';
    const NAME_ADMIN        = 'admin';
    
    public static $listActivity = [
        self::NAME_LIKE,
        self::NAME_COMMENT,
        self::NAME_FIRST_LIKE,
        self::NAME_TEN_LIKE,
        self::NAME_COMMENT_LIKE,
        self::NAME_AUTHOR_LIKE,
        self::NAME_POST_LIKE,
        self::NAME_ALL_LIKE,
        self::NAME_ADMIN,
    ];

    public static $nameTable = 'activity';

    /**
     * @param string $name
     * @return bool|array
     */
    public static function getByName($name)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE description = :name");
        $stmt->execute([
            'name' => $name,
        ]);

        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } else {
            $activityEntity = new ActivityEntity([
                'description' => $name,
                'scores'      => 1,
            ]);
            $activityEntity->save();
            
            return self::getByName($name);
        }
    }

    /**
     * @param int $id
     * @return bool|array
     */
    public static function getById($id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable . " WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
        ]);

        if ($stmt->rowCount()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } else {
            return false;
        }
    }

    /**
     * @return array|bool
     */
    public static function getAll()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable);
        $stmt->execute();

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