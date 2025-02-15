#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Commands\CreateTableCommand;
use App\Commands\HelpCommand;

function main(): void {
    $options = getopt("u:p:h:", ["file:", "create_table", "dry_run", "help"]);

    try {
        if (isset($options['help'])) {
            (new HelpCommand())->execute();
            exit(0);
        }

        if (isset($options['create_table'])) {
            (new CreateTableCommand($options))->execute();
            exit(0);
        }

        fwrite(STDERR, "Error: No valid options provided. Use --help for usage information.\n");
        exit(1);
    } catch (Throwable $e) {
        fwrite(STDERR, "Fatal Error: " . $e->getMessage() . "\n");
        exit(1);
    }
}

main();
