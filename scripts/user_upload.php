#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Commands\CreateTableCommand;
use App\Commands\HelpCommand;
use App\Commands\DryRunCommand;
use App\Utilities\CliHelper;

function main(): void {
    $options = CliHelper::getOptions();
    $executed = false;

    try {
        if (isset($options['help'])) {
            (new HelpCommand())->execute();
            exit(0);
        }

        if (isset($options['create_table'])) {
            (new CreateTableCommand($options))->execute();
            $executed = true;
        }

        if (isset($options['file'])) {
            if (isset($options['dry_run'])) {
                (new DryRunCommand($options['file']))->execute();
            }
            $executed = true;
        }

        if (!$executed) {
            fwrite(STDERR, "Error: No valid options provided. Use --help for usage information.\n");
            exit(1);
        }
        exit(0);
    } catch (Throwable $e) {
        fwrite(STDERR, "Fatal Error: " . $e->getMessage() . "\n");
        exit(1);
    }
}

main();
