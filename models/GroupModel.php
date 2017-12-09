<?php

/**
 */
class GroupModel extends BaseModel
{
    public static $nameTable = 'group';

    public static function getByAdminId($admin_id)
    {
        $stmt = self::$pdo->prepare(
            "SELECT * FROM " . self::$nameTable . " `group`," .
            AdminModel::$nameTable . " admin, " . self::$nameTable . AdminModel::$nameTable . "_link " .
            "WHERE group_id = group.id AND admin_id = admin.id AND admin_id = :admin_id"
        );
        $stmt->execute([
            'admin_id' => $admin_id
        ]);

        if ($stmt->rowCount()) {
            $res = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $res[$row['id']] = new GroupEntity($row);
            }
            return $res;
        } else {
            return false;
        }
    }
}