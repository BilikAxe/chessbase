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


    public function getByUser(int $userId): Cart|null
    {
        $result = $this->connection->prepare("SELECT * FROM carts WHERE user_id = ?");
        $result->execute([$userId]);

        $data = $result->fetch();

        if ($data) {
            $cart = new Cart($data['user_id']);
            $cart->setId($data['id']);

            return $cart;
        }

        return null;
    }


    public function save(Cart $cart): void
    {
        $result = $this->connection->prepare("INSERT INTO carts (user_id) VALUES (:userId)");
        $result->execute(['userId' => $cart->getUserId()]);
    }
}