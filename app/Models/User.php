<?php

namespace App\Models;

class User {
    private string $name;
    private string $surname;
    private string $email;

    public function __construct(string $name, string $surname, string $email) {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSurname(): string {
        return $this->surname;
    }

    public function getEmail(): string {
        return $this->email;
    }
}