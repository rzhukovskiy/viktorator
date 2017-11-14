<?php

/**
 * Created by PhpStorm.
 * User: rzhukovskiy
 * Date: 10.11.2017
 * Time: 18:14
 */
class BaseModel
{
    protected $pdo;
    protected $nameTable;
    protected $data;

    public function __construct()
    {
        $dsn = 'mysql:dbname=viktorator;host=127.0.0.1';
        $user = 'root';
        $password = 'vBghJk';

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $user, $password, $opt);
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $id int
     * @return BaseModel
     */
    public function getById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM :table WHERE id = :id');
        $stmt->execute([
            'table' => $this->nameTable,
            'id'    => $id
        ]);

        $nameClass = get_class();
        /** @var BaseModel $model */
        $model = new $nameClass();
        $model->setData($stmt);

        return $model;
    }
}