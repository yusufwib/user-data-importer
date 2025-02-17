<?php

namespace App\Commands;

use App\Database\DatabaseConfig;
use App\Database\DatabaseConnection;
use App\Repository\UserRepository;
use App\Services\CsvProcessor;
use App\Utilities\Constants;
use App\Utilities\Logger;
use App\Utilities\ImportResultPrinter;

class ImportCsvCommand {
    private $options;
    private $filePath;
    private $ignoreDuplicates;
    private $useTransactions;
    private $batchSize;

    public function __construct(array $options) {
        $this->options          = $options;
        $this->filePath         = $options['file'];
        $this->ignoreDuplicates = $options['ignore_duplicates'] ?? true;
        $this->useTransactions  = isset($options['use_transactions']);
        $this->batchSize        = $options['batch_size'] ?? Constants::DEFAULT_BATCH_SIZE;
    }

    public function execute(): void {
        Logger::log(Constants::LOG_TYPE_PLAIN, "Import CSV started...", true);
        Logger::log(Constants::LOG_TYPE_PLAIN, "Validating CSV...\n", true);

        $processor = new CsvProcessor();
        $result = $processor->processFile($this->filePath);

        ImportResultPrinter::printUsers("Valid users", $result['users']);
        ImportResultPrinter::printErrors("Validation errors", $result['errors']);
    
        Logger::log(Constants::LOG_TYPE_PLAIN, "\nDo you want to proceed with inserting valid users? (yes/no): ");
        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);
    
        if (strtolower($input) !== 'yes') {
            Logger::log(Constants::LOG_TYPE_ERROR, "Operation aborted. No users were inserted.");
            return;
        }
    
        $dbConfig = new DatabaseConfig($this->options);
        $db = new DatabaseConnection($dbConfig);
        $repository = new UserRepository($db->getConnection());
    
        $importResult = $repository->insertUsers(
            $result['users'],
            $this->ignoreDuplicates,
            $this->useTransactions,
            $this->batchSize
        );

        ImportResultPrinter::printErrors("Failed records", $importResult['failed_records']);
    
        Logger::log(
            Constants::LOG_TYPE_SUCCESS, 
            "Import complete. {$importResult['success_count']} users successfully inserted into the database.", 
            true
        );
    }    
}