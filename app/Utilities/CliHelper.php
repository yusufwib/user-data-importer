<?php

namespace App\Utilities;

class CliHelper {
    public static function getOptions(): array {
        return getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);
    }

    public static function printHelp(): void {
        echo "Usage: composer exec user_upload.php [options]\n\n";
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
        echo "--ignore_duplicates   Ignore duplicate emails (default is false).\n";
        echo "--use_transactions    Use database transactions (default is false).\n";
        echo "--batch_size          Number of users to process at once (default is 100).\n\n";

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
}