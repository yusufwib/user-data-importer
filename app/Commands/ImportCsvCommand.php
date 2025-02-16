<?php

namespace App\Commands;

use App\Database\DatabaseConfig;
use App\Database\DatabaseConnection;
use App\Repository\UserRepository;
use App\Services\CsvProcessor;
use App\Utilities\Constants;
use App\Utilities\CliHelper;

class ImportCsvCommand {
    private $options;
    private $filePath;
    private $ignoreDuplicates;
    private $useTransactions;

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

        foreach ($result['users'] as $u) {
            print_r($u);
        }

        foreach ($result['errors'] as $e) {
            print_r($e);
        }

        $dbConfig = new DatabaseConfig($this->options);
        $db = new DatabaseConnection($dbConfig);
        $repository = new UserRepository($db->getConnection());

        $repository->insertUsers($result['users'], $this->ignoreDuplicates, $this->useTransactions, $this->batchSize);
    }
}