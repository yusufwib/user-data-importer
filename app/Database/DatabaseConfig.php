<?php

namespace App\Database;
use App\Utilities\Constants;

class DatabaseConfig {
    private string $host;
    private string $username;
    private string $password;
    private string $dbName;

    public function __construct(array $options) {
        $this->host = $options['h'];
        $this->username = $options['u'];
        $this->password = $options['p'];
        $this->dbName = $options['db_name'] ?? Constants::DEFAULT_DB_NAME;
    }

    public function getHost(): string {
        return $this->host;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getDbName(): string {
        return $this->dbName;
    }
}
