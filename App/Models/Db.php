<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use PDOException;

class DB
{
    private PDO $pdo;

    public function __construct(
        private string $dsn,
        private string $username,
        private string $password,
        private ?array $options = null
    ) {
        try {
            $this->pdo = new PDO(
                $this->dsn,
                $this->username,
                $this->password,
                $this->options ?? []
            );

            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            echo "connection successful";
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}