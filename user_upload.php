#!/usr/bin/env php
<?php

function printHelp() {
    echo "Usage: php user_upload.php [options]\n\n";
    echo "Options:\n";
    echo "  --file [csvfile]      Process the specified CSV file.\n";
    echo "  --create_table        Build/Rebuild PostgreSQL 'users' table (requires -u -p -h).\n";
    echo "  --dry_run             Validate CSV without database changes.\n";
    echo "  -u                    PostgreSQL username (required for DB operations).\n";
    echo "  -p                    PostgreSQL password (required for DB operations).\n";
    echo "  -h                    PostgreSQL host (required for DB operations).\n";
    echo "  --help                Display this help message.\n\n";
    
    exit(0);
}

$options = getopt("", ["file:", "create_table", "dry_run", "help"]);

if (isset($options['help'])) {
    printHelp();
    exit(0);
}

if (isset($options['create_table'])) {
    if (!isset($options['u']) || !isset($options['p']) || !isset($options['h'])) {
        die("Error: Missing required options! Please provide -u (username), -p (password), and -h (host) when using --create_table.\n");
    }

    $host = $options['h'];
    $username = $options['u'];
    $password = $options['p'];
    $dbName = 'user_data_importer';

    try {
        $dsn = "pgsql:host=$host;dbname=$dbName";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("DROP TABLE IF EXISTS users");
        $pdo->exec("CREATE TABLE users (
            id      BIGSERIAL       PRIMARY KEY,
            name    VARCHAR(255)    NOT NULL,
            surname VARCHAR(255)    NOT NULL,
            email   VARCHAR(255)    UNIQUE NOT NULL
        )");

        echo "Success: The 'users' table has been created and is all set for use.\n";
    } catch (PDOException $e) {
        die("Error: Failed to create 'users' table. " . $e->getMessage() . "\n");
    }

    exit(0);
}

echo "Error: No valid options provided. Use --help for usage information.\n";
exit(1);
