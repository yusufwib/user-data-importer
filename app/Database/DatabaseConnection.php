<?php

namespace App\Database;

use PDO;
use PDOException;

class DatabaseConnection {
    private $connection;

    public function __construct(string $host, string $username, string $password, string $dbName) {
        $dsn = "pgsql:host=$host;dbname=$dbName";
        
        try {
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}