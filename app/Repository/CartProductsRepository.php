<?php

namespace banana\Repository;

use banana\Entity\Cart;
use banana\Entity\CartProduct;
use banana\Entity\Product;
use PDO;

class CartProductsRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function getOne(int $productId, int $userId): CartProduct|null
    {
        $result = $this->connection->prepare(
            "SELECT * FROM cart_products c_p
                   INNER JOIN carts c on c_p.cart_id = c.id
                   INNER JOIN users u on c.user_id = u.id
                   INNER JOIN products p on c_p.product_id = p.id
                   WHERE p.id = :productId and u.id = :userId"
        );
        $result->execute([
            'productId' => $productId,
            'userId' => $userId,
        ]);

        $data = $result->fetch();
//        echo "<pre>" . print_r($data, true) . "</pre>";die;
        if ($data) {
            $product = new Product(
                $data['name'],
                $data['price'],
                $data['category_id'],
                $data['img'],
            );

            $product->setId($data['product_id']);

            $cart = new Cart($data['user_id']);

            $cart->setId($data['cart_id']);

            return new CartProduct($product, $cart, $data['quantity']);
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
        if ($data) {
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

                $cartProducts = new CartProduct($product, $cart, $elem['quantity']);

                $products[] = $cartProducts;
            }
        }

        return $products;
    }


    public function save(CartProduct $product): void
    {
        $result = $this->connection->prepare("
                   INSERT INTO cart_products (
                           cart_id, 
                           product_id, 
                           quantity
                   ) VALUES (
                           :cartId,
                           :productId,
                           :quantity 
                   ) ON CONFLICT (cart_id, product_id) DO UPDATE 
                   SET quantity = EXCLUDED.quantity
        ");

        $result->execute([
            'cartId' => $product->getCart()->getId(),
            'productId' => $product->getProduct()->getId(),
            'quantity' => $product->getQuantity() + 1,
        ]);
    }


    public function getQuantityByCart(int $cartId): int
    {
        $result = $this->connection->prepare(
            "SELECT SUM(quantity) FROM cart_products WHERE cart_id = ? GROUP BY cart_id"
        );
        $result->execute([$cartId]);

        $data = $result->fetch();

        if ($data){
            return $data['sum'];
        }

        return 0;
    }
}