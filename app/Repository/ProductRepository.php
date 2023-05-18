<?php

namespace banana\Repository;

use banana\Entity\Cart;
use banana\Entity\CartProducts;
use banana\Entity\Product;
use PDO;

class ProductRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function saveProductInCart(CartProducts $cartProducts): void
    {

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


    public function getProductByUser(int $userId): array
    {
        $result = $this->connection->prepare(
             "SELECT * FROM cart_products c_p
                    INNER JOIN carts c on c_p.cart_id = c.id
                    INNER JOIN products p on c_p.product_id = p.id
                    INNER JOIN users u on c.user_id = u.id
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

            $cart = new Cart($elem['user_id']);

            $cart->setId($elem['cart_id']);

            $cartProducts = new CartProducts($product, $cart, $elem['quantity']);

            $products[] = $cartProducts;
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