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
        if (!file_exists($this->filePath)) {
            throw new \RuntimeException("CSV file not found: " . $this->filePath);
        }

        echo "Dry run complete\n";
    }
}