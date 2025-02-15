<?php

namespace App\Commands;

use App\Database\DatabaseConnection;
use App\Repository\UserRepository;
use App\Utilities\CliHelper;

class CreateTableCommand {
    private $options;

    public function __construct(array $options) {
        $this->options = $options;
    }

    public function execute(): void {
        if (!isset($this->options['u']) || !isset($this->options['h']) || !isset($this->options['p'])) {
            throw new \InvalidArgumentException("Missing required options! Please provide -u (username), -p (password), and -h (host) when using --create_table");
        }

        $db = new DatabaseConnection(
            $this->options['h'],
            $this->options['u'],
            $this->options['p'],
            'user_data_importer'
        );

        $repository = new UserRepository($db->getConnection());
        $repository->createTable();
        
        echo "Success: Users table has been created and is all set for use.\n";
    }
}