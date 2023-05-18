<?php

namespace banana\Entity;

class Cart
{
    private int $id;
    private int $userId;


    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }


    public function getCartId(): int
    {
        return $this->id;
    }


    public function setCartId(int $id): void
    {
        $this->id = $id;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }

}