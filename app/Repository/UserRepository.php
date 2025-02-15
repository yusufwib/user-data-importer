<?php

namespace App\Repository;

use App\Models\User;
use PDO;

class UserRepository {
    private $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function createTable(): void {
        $this->connection->exec("DROP TABLE IF EXISTS users");
        $this->connection->exec("
            CREATE TABLE users (
                id      BIGSERIAL PRIMARY KEY,
                name    VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                email   VARCHAR(255) UNIQUE NOT NULL
            )
        ");
    }
}