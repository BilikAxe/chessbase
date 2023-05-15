<?php

namespace banana\Repository;

use banana\Entity\Category;
use banana\Entity\Product;
use PDO;

class ProductRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getProduct(int $id): Product|null
    {
        $result = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
        $result->execute([$id]);
        $data = $result->fetch();

        if ($data) {
            $product = new Product(
                $data['name'],
                $data['price'],
                $data['parent'],
                $data['img']
            );

            $product->setId($data['id']);

            return $product;
        }

        return null;
    }

    public function getAllProducts(): array
    {
        $products = [];

        $result = $this->connection->query("SELECT * FROM products");

        $data = $result->fetchAll();

        foreach ($data as $elem) {
            $product = new Product(
                $elem['name'],
                $elem['price'],
                $elem['parent'],
                $elem['img']
            );

            $product->setId($elem['id']);

            $products[$elem['id']] = $product;
        }

        return $products;
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