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
        $lineNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;
            $users[] = new User(
                $row[0] ?? '',
                $row[1] ?? '',
                $row[2] ?? ''
            );
        }

        fclose($handle);
        return $users;
    }
}