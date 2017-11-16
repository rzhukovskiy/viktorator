<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 16.11.2017
 * Time: 15:22
 */
class ConfigModel extends BaseModel
{
    protected $nameTable = 'config';

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `$this->nameTable`");
        $stmt->execute();

        return new ConfigEntity($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function clearAll()
    {
        $stmt = $this->pdo->prepare("DELETE FROM `$this->nameTable` WHERE id > 0");
        $stmt->execute();
    }
}