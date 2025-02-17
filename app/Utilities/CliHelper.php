<?php

namespace App\Utilities;

class CliHelper {
    public static function getOptions(): array {
        return getopt("u:p:h:", [
            "file:",
            "create_table",
            "dry_run",
            "help",
            "batch_size:",
            "db_name:",
            "check_duplicates",
            "use_transactions",
        ]);
    }

    public static function printHelp(): void {
        echo "\nUsage: composer exec user_upload.php [options]\n\n";
        echo "Options:\n";
        echo "--file [csvfile]      Process the specified CSV file.\n";
        echo "--create_table        Build or rebuild the PostgreSQL users table and exit.\n";
        echo "--dry_run             Process the CSV without inserting into the database.\n";
        echo "-u                    PostgreSQL username (required for database operations).\n";
        echo "-p                    PostgreSQL password (required for database operations).\n";
        echo "-h                    PostgreSQL host (required for database operations).\n";
        echo "--help                Display this help message.\n\n";

        echo "Additional Options:\n";
        echo "--db_name             PostgreSQL database name (default is 'user_data_importer').\n";
        echo "--check_duplicates    Check duplicate emails (default is false).\n";
        echo "--use_transactions    Use database transactions (default is false).\n";

        echo "Examples:\n";
        echo "  1. Create Table & Import Data:\n";
        echo "     composer exec user_upload.php -- --create_table -u postgres -p supersecret -h localhost\n";
        echo "     composer exec user_upload.php -- --file data/users.csv -u postgres -p supersecret -h localhost\n";
        echo "     composer exec user_upload.php -- --create_table --file data/users.csv -u postgres -p supersecret -h localhost\n\n";
        echo "  2. Dry Run (Validate CSV):\n";
        echo "     composer exec user_upload.php -- --file data/users.csv --dry_run\n\n";

        echo "Notes:\n";
        echo "  - CSV Format: Must have 3 columns (name, surname, email) with a header row.\n";
        echo "  - Email: Lowercased and validated.\n";
        echo "  - Name/Surname: Auto-capitalized.\n";
    }

    public static function getDuplicateEmail($message): string {
        preg_match("/Key \\(email\\)=\\((.*?)\\) already exists/", $message, $matches);
        return $matches[1] ?? "Unknown Email";
    }
}