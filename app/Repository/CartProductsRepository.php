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


    public function getQuantity(int $productId): int|null
    {
        $result = $this->connection->prepare("SELECT quantity FROM cart_products WHERE product_id = ?");
        $result->execute([$productId]);

        $data = $result->fetch();

        if ($data) {
            return (int)$data;
        }

        return null;
    }


    public function getProductsByUser(int $userId): array
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


    public function saveProductInCart(CartProducts $product): void
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
            'product_id' => $product->getProducts()->getId(),
            'quantity' => $product->getQuantity()
        ]);
    }


    public function addQuantity(int $quantity, int $productId): void
    {
        $result = $this->connection->prepare("
                UPDATE cart_products SET quantity = :quantity WHERE product_id = :productId
        ");

        $result->execute([
            'quantity' => $quantity,
            'product_id' => $productId,
        ]);
    }
}