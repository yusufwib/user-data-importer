<?php

namespace App\Repository;

use App\Models\User;
use PDO;
use App\Utilities\Constants;
use App\Utilities\CliHelper;

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
        bool $checkDuplicates = false, 
        bool $useTransaction = false
    ): array {
        $successfulInserts = 0;
        $failedInserts = [];
        $batches = array_chunk($users, Constants::DEFAULT_BATCH_SIZE);

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
    
                if (!$checkDuplicates) {
                    $sql .= " ON CONFLICT (email) DO NOTHING";
                }
    
                $stmt = $this->connection->prepare($sql);
                $stmt->execute($values);

                $successfulInserts += $stmt->rowCount();
            }
    
            if ($useTransaction) {
                $this->connection->commit();
            }
        } catch (\Throwable $e) {
            if ($useTransaction) {
                $this->connection->rollBack();
            }

            if ($e->getCode() === Constants::PG_DUPLICATE_ERROR_CODE) {
                $duplicateEmail = CliHelper::getDuplicateEmail($e->getMessage());
                $failedInserts[] = "Duplicate email: $duplicateEmail";
            } else {
                $failedInserts[] = "Database error - " . $e->getMessage() . ".";
            }
        }
    
        return [
            'success_count'     => $successfulInserts,
            'failed_records'    => $failedInserts,
        ];
    }
}