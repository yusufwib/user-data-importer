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

    public function insertUsers(
        array $users, 
        bool $ignoreDuplicates = false, 
        bool $useTransaction = false,
        int $batchSize = 100
    ): void {
        $batches = array_chunk($users, $batchSize);
        if ($useTransaction) {
            $this->connection->beginTransaction();
        }
    
        try {
            foreach ($batches as $batch) {
                $placeholders = [];
                $values = [];
    
                foreach ($batch as $index => $user) {
                    $placeholders[] = "(:name{$index}, :surname{$index}, :email{$index})";
                    $values[":name{$index}"] = $user->getName();
                    $values[":surname{$index}"] = $user->getSurname();
                    $values[":email{$index}"] = $user->getEmail();
                }
    
                $sql = "INSERT INTO users (name, surname, email) VALUES " . implode(", ", $placeholders);
    
                if ($ignoreDuplicates) {
                    $sql .= " ON CONFLICT (email) DO NOTHING";
                }
    
                $stmt = $this->connection->prepare($sql);
                $stmt->execute($values);
            }
    
            if ($useTransaction) {
                $this->connection->commit();
            }
        } catch (\Throwable $e) {
            
            // TODO: add pg errcodes appendix for unq constraint

            if ($useTransaction) {
                $this->connection->rollBack();
            }
            throw new \RuntimeException("Failed to insert users: " . $e->getMessage());
        }
    }
}