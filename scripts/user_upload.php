#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Commands\CreateTableCommand;
use App\Commands\HelpCommand;
use App\Commands\DryRunCommand;
use App\Commands\ImportCsvCommand;
use App\Utilities\CliHelper;
use App\Utilities\Constants;
use App\Utilities\Logger;

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
            } else {
                (new ImportCsvCommand($options))->execute();
            }

            $executed = true;
        }

        if (!$executed) {
            Logger::log(Constants::LOG_TYPE_ERROR, 'No valid options provided. Use --help for usage information.');
            exit(1);
        }
        exit(0);
    } catch (Throwable $e) {
        Logger::log(Constants::LOG_TYPE_ERROR, 'Fatal Error: ' . $e->getMessage(), true);
        exit(1);
    }
}

main();
