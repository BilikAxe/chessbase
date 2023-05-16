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


    public function getCart(int $userId): Cart
    {
        $result = $this->connection->prepare("SELECT * FROM carts WHERE user_id = ?");
        $result->execute([$userId]);

        $data = $result->fetchAll();

        return new Cart($data['user_id']);
    }
}