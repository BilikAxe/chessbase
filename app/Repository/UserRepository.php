<?php

namespace banana\Repository;

use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(string $email, string $firstName, string $lastName,
                           string $surname, string $phoneNumber, string $password): void
    {
        $sth = $this->connection->prepare("
                INSERT INTO users (email, first_name, last_name, surname, phone_number, password) 
                VALUES (:email, :first_name, :last_name, :surname, :phone_number, :password)
                ");

        $sth->execute([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'surname' => $surname,
            'phone_number' => $phoneNumber,
            'password' => $password
        ]);
    }



    public function getEmail(array $data): bool
    {
        $result = $this->connection->prepare("SELECT email FROM users WHERE email = ?");
        $result->execute($data['email']);

        return $result->fetch();
    }
}