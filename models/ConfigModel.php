<?php

/**
 */
class ConfigModel extends BaseModel
{
    public static $nameTable = 'config';

    public static function get()
    {
        $stmt = self::$pdo->prepare("SELECT * FROM " . self::$nameTable);
        $stmt->execute();
        
        if ($stmt->rowCount()) {
            return new CommentEntity($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            return false;
        }
    }
}