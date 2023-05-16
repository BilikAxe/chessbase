<?php

namespace banana\Entity;

class User
{
    private int $id;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $surname;
    private string $phoneNumber;
    private string $password;
    private int $cartId;


    public function __construct(
        string $email,
        string $firstName,
        string $lastName,
        string $surname,
        string $phoneNumber,
        string $password,
    ) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->surname = $surname;
        $this->phoneNumber = $phoneNumber;
        $this->password = $password;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function getSurname(): string
    {
        return $this->surname;
    }


    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }


    public function getPassword(): string
    {
        return $this->password;
    }


    public function setCart(int $cartId): void
    {
        $this->cartId = $cartId;
    }


    public function getCart(): int
    {
        return $this->cartId;
    }
}