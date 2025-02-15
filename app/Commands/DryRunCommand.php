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
        $users = $processor->processFile($this->filePath);

        foreach ($users as $u) {
            print_r($u);
        }

        echo "Dry run complete\n";
    }
}