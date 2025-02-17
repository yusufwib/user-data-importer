<?php

namespace App\Commands;

use App\Services\CsvProcessor;
use App\Utilities\Constants;
use App\Utilities\Logger;

class DryRunCommand {
    private $filePath;

    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    public function execute(): void {
        Logger::log(Constants::LOG_TYPE_PLAIN, "Dry run started...\n", true);

        $processor = new CsvProcessor();
        $result = $processor->processFile($this->filePath);

        if (!empty($result['users'])) {
            Logger::log(Constants::LOG_TYPE_PLAIN, "✅ Valid Users:\n");
    
            foreach ($result['users'] as $idx => $user) {
                Logger::log(
                    Constants::LOG_TYPE_PLAIN, 
                    sprintf(
                        "%d. Name:    %s\n   Surname: %s\n   Email:   %s\n",
                        $idx + 1,
                        $user->getName(),
                        $user->getSurname(),
                        $user->getEmail()
                    )
                );
            }
        }
    
        if (!empty($result['errors'])) {
            Logger::log(Constants::LOG_TYPE_PLAIN, "\n❌ Errors:\n");

            foreach ($result['errors'] as $idx => $error) {
                Logger::log(Constants::LOG_TYPE_PLAIN, sprintf(" %d. %s\n", $idx + 1, $error));
            }
        }

        Logger::log(Constants::LOG_TYPE_SUCCESS, "Dry run complete.", true);
    }
}