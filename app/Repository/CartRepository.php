<?php

namespace banana\Repository;

use banana\Entity\Cart;
use PDO;

class CartRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function getCart(int $cartId): Cart
    {
        $result = $this->connection->prepare("SELECT * FROM cart_products WHERE cart_id = ?");
        $result->execute([$cartId]);

        $data = $result->fetchAll();

        return new Cart($data['user_id']);
    }
}