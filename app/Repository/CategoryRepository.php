<?php

namespace banana\Repository;

use banana\Entity\Category;
use PDO;

class CategoryRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function getCategories(): array
    {
        $categories = [];

        $result = $this->connection->query("SELECT * FROM categories");

        $data = $result->fetchAll();

        foreach ($data as $elem) {
            $category = new Category(
                $elem['name'],
                $elem['img']
            );

            $category->setId($elem['id']);

            $categories[$elem['id']] = $category;
        }

        return $categories;
    }
}