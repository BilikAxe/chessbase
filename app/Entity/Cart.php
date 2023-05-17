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


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


    public function getUserId(): int
    {
        return $this->userId;
    }


    public function getProducts(): array
    {
        return $this->products;
    }


    public function addProduct(Product $product): void
    {
        $this->products = $product['name'];
    }


    public function getGrandTotal(): int
    {
        $this->grandTotal = 0;


        return $this->grandTotal;
    }
}