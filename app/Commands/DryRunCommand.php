<?php

namespace App\Commands;

use App\Services\CsvProcessor;
use App\Utilities\CliHelper;

class DryRunCommand {
    private $filePath;

    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    public function execute(): void {
        $processor = new CsvProcessor();
        $result = $processor->processFile($this->filePath);

        foreach ($result['users'] as $u) {
            print_r($u);
        }

        foreach ($result['errors'] as $e) {
            print_r($e);
        }

        echo "Dry run complete\n";
    }
}