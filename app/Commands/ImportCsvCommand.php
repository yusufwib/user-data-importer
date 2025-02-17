<?php

namespace App\Commands;

use App\Database\DatabaseConfig;
use App\Database\DatabaseConnection;
use App\Repository\UserRepository;
use App\Services\CsvProcessor;
use App\Utilities\Constants;
use App\Utilities\Logger;

class ImportCsvCommand {
    private $options;
    private $filePath;
    private $ignoreDuplicates;
    private $useTransactions;
    private $batchSize;

    public function __construct(array $options) {
        $this->options          = $options;
        $this->filePath         = $options['file'];
        $this->ignoreDuplicates = isset($options['ignore_duplicates']);
        $this->useTransactions  = isset($options['use_transactions']);
        $this->batchSize        = $options['batch_size'] ?? Constants::DEFAULT_BATCH_SIZE;
    }

    public function execute(): void {
        $processor = new CsvProcessor();
        $result = $processor->processFile($this->filePath);
    
        // Print valid users
        if (!empty($result['users'])) {
            Logger::log(Constants::LOG_TYPE_PLAIN, "✅ Valid Users:\n");
    
            foreach ($result['users'] as $idx => $user) {
                Logger::log(
                    Constants::LOG_TYPE_PLAIN, 
                    sprintf(
                        "%d. Name: %s, Surname: %s, Email: %s",
                        $idx + 1,
                        $user->getName(),
                        $user->getSurname(),
                        $user->getEmail()
                    )
                );
            }
        }
    
        // Print errors
        if (!empty($result['errors'])) {
            Logger::log(Constants::LOG_TYPE_PLAIN, "\n❌ Errors:\n");
    
            foreach ($result['errors'] as $idx => $error) {
                Logger::log(Constants::LOG_TYPE_PLAIN, sprintf(" %d. %s", $idx + 1, $error));
            }
        }
    
        // Ask for confirmation
        Logger::log(Constants::LOG_TYPE_PLAIN, "\nDo you want to proceed with inserting valid users? (yes/no): ");
        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);
    
        if (strtolower($input) !== 'yes') {
            Logger::log(Constants::LOG_TYPE_WARNING, "Operation aborted. No users were inserted.");
            return;
        }
    
        // Proceed with insertion if confirmed
        $dbConfig = new DatabaseConfig($this->options);
        $db = new DatabaseConnection($dbConfig);
        $repository = new UserRepository($db->getConnection());
    
        $repository->insertUsers($result['users'], $this->ignoreDuplicates, $this->useTransactions, $this->batchSize);
    
        Logger::log(Constants::LOG_TYPE_SUCCESS, "✅ Users successfully inserted into the database.");
    }    
}