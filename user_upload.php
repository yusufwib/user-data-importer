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

$options = getopt("", ["help"]);

if (isset($options['help'])) {
    printHelp();
    exit(0);
}
