<?php

namespace banana\Entity;

class Product
{
    private int $id;
    private string $name;
    private int $price;
    private string $img = '';


    public function __construct(string $name, int $price)
    {
        $this->name = $name;
        $this->price = $price;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }
    

    public function getName(): string
    {
        return $this->name;
    }


    public function getPrice(): int
    {
        return $this->price;
    }


    public function getImg(): string
    {
        return $this->img;
    }


    public function setImg(string $img): void
    {
        $this->img = $img;
    }
}