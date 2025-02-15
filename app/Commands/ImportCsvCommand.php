<?php

namespace App\Commands;

use App\Database\DatabaseConnection;
use App\Repository\UserRepository;
use App\Services\CsvProcessor;
use App\Utilities\CliHelper;

class ImportCsvCommand {
    private $options;
    private $filePath;

    public function __construct(array $options) {
        $this->options = $options;
        $this->filePath = $options['file'];
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

        $db = new DatabaseConnection(
            $this->options['h'],
            $this->options['u'],
            $this->options['p'],
            'user_data_importer'
        );

        $repository = new UserRepository($db->getConnection());
        
        foreach ($result['users'] as $user) {
            $repository->insertUser($user);
        }
    }
}