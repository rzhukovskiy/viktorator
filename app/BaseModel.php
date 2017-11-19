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

    public function __construct()
    {
        $dsn = 'mysql:dbname=viktorator;host=127.0.0.1';
        $user = 'root';
        $password = '';
        //$password = 'vBghJk';

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new PDO($dsn, $user, $password, $opt);
    }

    public function save($params)
    {
        if (empty($params['id'])) {
            $columns = implode("`, `", array_keys($params));
            $values  = implode("', '", array_values($params));

            $stmt = $this->pdo->prepare("INSERT INTO $this->nameTable (`$columns`) VALUES ('$values')");
            $stmt->execute();

            return $this->pdo->lastInsertId();
        } else {
            $values = [];
            foreach ($params as $name => $value) {
                $values[] = "`$name` = '$value'";
            }
            $values = implode(", ", $values);
            $stmt = $this->pdo->prepare("UPDATE $this->nameTable SET $values WHERE id = :id");
            $stmt->execute([
                ':id' => $params['id'],
            ]);

            return $params['id'];
        }
    }
}