<?php

namespace banana\Entity;

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private int $parent;
    private string $img;


    public function __construct(string $name, float $price, int $parent, string $img)
    {
        $this->name = $name;
        $this->price = $price;
        $this->parent = $parent;
        $this->img = $img;
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


    public function getPrice(): float
    {
        return $this->price;
    }


    public function getImg(): string
    {
        return $this->img;
    }


    public function getParent(): int
    {
        return $this->parent;
    }
}