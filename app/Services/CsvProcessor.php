<?php

namespace App\Services;

use App\Models\User;
use App\Utilities\CliHelper;

class CsvProcessor {
    public function processFile(string $filePath): array {
        if (!file_exists($filePath)) {
            throw new \RuntimeException("File not found: $filePath");
        }

        $handle = fopen($filePath, 'r');
        $users = [];
        $errors = [];
        $lineNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            try {
                $users[] = new User(
                    $this->formatName($row[0] ?? ''),
                    $this->formatName($row[1] ?? ''),
                    $this->validateEmail($row[2] ?? '')
                );
            } catch (\InvalidArgumentException $e) {
                $errors[] = "Line $lineNumber: " . $e->getMessage();
            }
        }

        fclose($handle);

        return [
            'users' => $users,
            'errors' => $errors
        ];
    }

    private function formatName(string $name): string {
        $name = trim($name);
        return ucfirst(strtolower($name));
    }

    private function validateEmail(string $email): string {
        $email = strtolower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format: $email");
        }
        return $email;
    }
}