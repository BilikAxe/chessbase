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

    public function save(User $user): void
    {
        $sth = $this->connection->prepare("
                INSERT INTO users (
                        email, 
                        first_name, 
                        last_name, 
                        surname, 
                        phone_number, 
                        password
                ) VALUES (
                        :email, 
                        :first_name, 
                        :last_name, 
                        :surname, 
                        :phone_number, 
                        :password)
                ");

        $sth->execute([
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'surname' => $user->getSurname(),
            'phone_number' => $user->getPhoneNumber(),
            'password' => $user->getPassword()
        ]);
    }



    public function getEmail(string $email): object
    {
        $result = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $result->execute([$email]);

        $data = $result->fetch();

        $user =  new User(
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $data['surname'],
            $data['phone_number'],
            $data['password']
        );

        $user->setId($data['id']);

        return $user;
    }
}