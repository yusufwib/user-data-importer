<?php

namespace App\Utilities;

class CliHelper {
    public static function getOptions(): array {
        return getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);
    }

    public static function printHelp(): void {
        echo "Usage: php user_upload.php [options]\n\n";
        echo "Options:\n";
        echo "--file [csvfile]    Process the specified CSV file.\n";
        echo "--create_table      Build or rebuild the PostgreSQL users table and exit.\n";
        echo "--dry_run           Process the CSV without inserting into the database.\n";
        echo "-u                  PostgreSQL username (required for database operations).\n";
        echo "-p                  PostgreSQL password (required for database operations).\n";
        echo "-h                  PostgreSQL host (required for database operations).\n";
        echo "--help              Display this help message.\n\n";
    }
}