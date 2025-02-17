<?php

namespace App\Utilities;

class ImportResultPrinter {
    public static function printUsers(string $title, array $users): void {
        if (empty($users)) {
            return;
        }

        Logger::log(Constants::LOG_TYPE_PLAIN, "✅ $title:\n");
        foreach ($users as $idx => $user) {
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

    public static function printErrors(string $title, array $errors): void {
        if (empty($errors)) {
            return;
        }

        Logger::log(Constants::LOG_TYPE_PLAIN, "\n❌ $title:\n");
        foreach ($errors as $idx => $error) {
            Logger::log(Constants::LOG_TYPE_PLAIN, sprintf(" %d. %s\n", $idx + 1, $error));
        }
    }
}
