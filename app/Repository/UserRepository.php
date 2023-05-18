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



    public function getUserByEmail(string $email): User|null
    {
        $result = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        return $this->extracted($result, $email);
    }


    public function getUserByUserId(int $userId): User|null
    {
        $result = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
        return $this->extracted($result, $userId);
    }


    public function extracted(bool|\PDOStatement $result, int $userId): ?User
    {
        $result->execute([$userId]);

        $data = $result->fetch();

        if ($data) {
            $user = new User(
                $data['email'],
                $data['first_name'],
                $data['last_name'],
                $data['surname'],
                $data['phone_number'],
                $data['password'],
            );

            $user->setCartId($data['cart_id']);
            $user->setId($data['id']);

            return $user;
        }

        return null;
    }
}