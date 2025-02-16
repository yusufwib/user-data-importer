<?php

namespace App\Database;

use App\Database\DatabaseConfig;
use PDO;
use PDOException;

class DatabaseConnection {
    private $connection;
    private DatabaseConfig $config;

    public function __construct(DatabaseConfig $config) {
        $this->config = $config;
        $dsn = "pgsql:host={$config->getHost()};dbname={$config->getDbName()}";

        try {
            $this->connection = new PDO($dsn, $config->getUsername(), $config->getPassword());
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
