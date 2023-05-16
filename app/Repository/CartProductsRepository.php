<?php

namespace banana\Repository;

use PDO;

class CartProductsRepository
{
    private PDO $connection;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
}