<?php

namespace banana\Entity;

class Category
{
    private int $id;
    private string $name;
    private string $img;



    public function __construct(string $name, string $img)
    {
        $this->name = $name;
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


    public function getImg(): string
    {
        return $this->img;
    }
}