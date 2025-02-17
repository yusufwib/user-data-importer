<?php

namespace App\Utilities;

class Logger {
    public static function log(string $type, string $message, bool $withTimestamp = false): void  {
        $timestamp = $withTimestamp ? "[" . date('Y-m-d H:i:s') . "] " : "";
        $logTypes = [
            Constants::LOG_TYPE_SUCCESS => ["✅", "\033[32m"],
            Constants::LOG_TYPE_ERROR   => ["❌", "\033[31m"],
            Constants::LOG_TYPE_PLAIN   => ["", "\033[0m"],
        ];

        $logType = $logTypes[$type] ?? $logTypes[Constants::LOG_TYPE_PLAIN];
        $formattedLogMessage = "\n{$logType[1]}{$timestamp}{$logType[0]}$message\033[0m";

        fwrite(STDOUT, $formattedLogMessage);
    }
}