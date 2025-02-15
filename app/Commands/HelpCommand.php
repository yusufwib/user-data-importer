<?php

namespace App\Commands;

use App\Utilities\CliHelper;

class HelpCommand {
    public function execute(): void {
        CliHelper::printHelp();
    }
}