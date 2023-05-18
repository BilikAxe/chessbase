<?php

namespace banana\Repository;

use banana\Entity\Product;
use PDO;

class ProductRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function getProduct(int $productId): Product
    {
        $result = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
        $result->execute([$productId]);

        $data = $result->fetch();

        $product = new Product(
            $data['name'],
            $data['price'],
            $data['category_id'],
            $data['img'],
        );

        $product->setId($data['id']);

        return $product;
    }


    public function getProductsByCategory(int $categoryId): array
    {
        $products = [];

        $result = $this->connection->prepare("SELECT * FROM products WHERE category_id = ?");
        $result->execute([$categoryId]);

        $data = $result->fetchAll();

        foreach ($data as $elem) {
            $product = new Product(
                $elem['name'],
                $elem['price'],
                $elem['category_id'],
                $elem['img']
            );

            $product->setId($elem['id']);

            $products[$elem['id']] = $product;
        }

        return $products;
    }


}