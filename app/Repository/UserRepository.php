<?php

namespace banana\Repository;

use banana\Entity\User;
use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User
    {
        $sth = $this->connection->prepare("
                INSERT INTO users (email, first_name, last_name, surname, phone_number, password) 
                VALUES (:email, :first_name, :last_name, :surname, :phone_number, :password)
                ");

        $sth->execute([
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'surname' => $user->getSurname(),
            'phone_number' => $user->getPhoneNumber(),
            'password' => $user->getPassword()
        ]);

        return $user;
    }



    public function getEmail(string $email): bool|array
    {
        $result = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute([$email]);

        return $result->fetch();
    }
}