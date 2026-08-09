<?php
declare(strict_types=1);
namespace App;
use PDO;
use PDOException;


class Database
{
    private PDO $pdo;
    public function __construct($host, $dbname, $username, $password, array $options = [])
    {
        try {

            $dsn = "mysql:host=$host;dbname=$dbname";

            $this->pdo = new \PDO($dsn, $username, $password, $options);


            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo'connection successful';


        } catch (PDOException $e) {

            throw new PDOException("Database Connection Failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }
    public function getdb()
    {
        return $this->pdo;
    }
}


