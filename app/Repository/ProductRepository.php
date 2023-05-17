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


    public function getProductByUser(int $userId): array
    {
        $result = $this->connection->prepare(
             "SELECT * FROM products p
                    INNER JOIN cart_products c_p ON c_p.product_id = p.id 
                    INNER JOIN carts c ON c.id = c_p.cart_id
                    INNER JOIN users u ON u.cart_id = c.id
                    WHERE u.id = ?"
        );

        $result->execute([$userId]);
        $data = $result->fetchAll();

        $products = [];

        foreach ($data as $elem) {
            $product = new Product(
                $elem['name'],
                $elem['price'],
                $elem['category_id'],
                $elem['img']
            );

            $product->setId($elem['id']);

            $products[] = $product;
        }

        return $products;
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