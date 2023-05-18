<?php

namespace banana\Repository;

use banana\Entity\Cart;
use banana\Entity\CartProducts;
use banana\Entity\Product;
use PDO;

class CartProductsRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function getOne(int $productId): CartProducts|null
    {
        $result = $this->connection->prepare(
            "SELECT * FROM cart_products c_p
                   INNER JOIN carts c on c_p.cart_id = c.id
                   INNER JOIN users u on c.user_id = u.id
                   INNER JOIN products p on c_p.product_id = p.id
                   WHERE p.id = ?"
        );
        $result->execute([$productId]);

        $data = $result->fetch();
//        echo "<pre>" . print_r($data, true) . "</pre>";die;
        if ($data) {
            $product = new Product(
                $data['name'],
                $data['price'],
                $data['category_id'],
                $data['img'],
            );

            $product->setId($data['id']);

            $cart = new Cart($data['user_id']);

            $cart->setCartId($data['cart_id']);

            return new CartProducts($product, $cart, $data['quantity']);
        }

        return null;
    }


    public function getByUser(int $userId): array
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

            $cart->setCartId($elem['cart_id']);

            $cartProducts = new CartProducts($product, $cart, $elem['quantity']);

            $products[] = $cartProducts;
        }

        return $products;
    }


    public function updateQuantity(CartProducts $cartProduct): void
    {
        $result = $this->connection->prepare("UPDATE cart_products SET quantity = :quantity WHERE product_id = :productId");
        $result->execute([
            'quantity' => $cartProduct->getQuantity(),
            'productId' => $cartProduct->getProduct()->getId(),
        ]);
    }


    public function save(CartProducts $product): void
    {
        $result = $this->connection->prepare("
                   INSERT INTO cart_products (
                           cart_id, 
                           product_id, 
                           quantity
                   ) VALUES (
                           :cart_id,
                           :product_id,
                           :quantity 
                   )
        ");

        $result->execute([
            'cart_id' => $product->getCart()->getCartId(),
            'product_id' => $product->getProduct()->getId(),
            'quantity' => $product->getQuantity()
        ]);
    }



}