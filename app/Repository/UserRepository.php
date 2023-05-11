<?php

namespace banana\Repository;

use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO("pgsql:host=db;dbname=dbname", 'dbuser', 'dbpwd');
    }

    public function create($data): void
    {
        $email = $this->clearData($data['email']);
        $firstName = $this->clearData($data['firstName']);
        $lastName = $this->clearData($data['lastName']);
        $surname = $this->clearData($data['surname']);
        $phoneNumber = $this->clearData($data['phoneNumber']);
        $password = $data['password'];

        $password = password_hash($password, PASSWORD_DEFAULT);

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

    private function clearData(string $val): string
    {
        $val = trim($val);
        $val = stripslashes($val);
        $val = strip_tags($val);
        return htmlspecialchars($val);
    }

    public function getEmail(array $data): bool
    {
        $result = $this->connection->prepare("SELECT email FROM users WHERE email = ?");
        $result->execute($data['email']);

        return $result->fetch();
    }
}