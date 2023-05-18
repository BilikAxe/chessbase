<?php

namespace banana\Repository;

use banana\Entity\Cart;
use PDO;

class CartRepository
{
    private PDO $connection;


    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }


    public function getCartByCartId(int $cartId): Cart
    {
        $result = $this->connection->prepare("SELECT * FROM carts WHERE id = ?");
        $result->execute([$cartId]);

        $data = $result->fetch();

        $cart = new Cart($data['user_id']);
        $cart->setCartId($data['id']);

        return $cart;

    }
}