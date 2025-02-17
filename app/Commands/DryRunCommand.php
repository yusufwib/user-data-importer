<?php

namespace App\Commands;

use App\Services\CsvProcessor;
use App\Utilities\Constants;
use App\Utilities\Logger;
use App\Utilities\ImportResultPrinter;

class DryRunCommand {
    private $filePath;

    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    public function execute(): void {
        Logger::log(Constants::LOG_TYPE_PLAIN, "Dry run started...", true);
        Logger::log(Constants::LOG_TYPE_PLAIN, "Validating CSV...\n", true);

        $processor = new CsvProcessor();
        $result = $processor->processFile($this->filePath);

        ImportResultPrinter::printUsers("Valid users", $result['users']);
        ImportResultPrinter::printErrors("Validation errors", $result['errors']);

        Logger::log(Constants::LOG_TYPE_SUCCESS, "Dry run complete.", true);
    }
}