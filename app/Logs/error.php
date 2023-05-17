An error occurred while processing the request: 
{"Message":"Too few arguments to function banana\\Repository\\CartRepository::getCart(), 0 passed in \/var\/www\/html\/app\/Controllers\/CartController.php on line 26 and exactly 1 expected","File":"\/var\/www\/html\/app\/Repository\/CartRepository.php","Line":19}

An error occurred while processing the request: 
{"Message":"banana\\Controllers\\CartController::__construct(): Argument #1 ($cartRepository) must be of type banana\\Repository\\CartRepository, banana\\Repository\\ProductRepository given, called in \/var\/www\/html\/app\/Configs\/dependencies.php on line 63","File":"\/var\/www\/html\/app\/Controllers\/CartController.php","Line":12}

An error occurred while processing the request: 
{"Message":"SQLSTATE[42601]: Syntax error: 7 ERROR:  syntax error at or near \"=\"\nLINE 3: ...                   JOIN carts c ON c.id = c_p.cart_id = c.id\n                                                                 ^","File":"\/var\/www\/html\/app\/Repository\/ProductRepository.php","Line":29}

An error occurred while processing the request: 
{"Message":"SQLSTATE[42601]: Syntax error: 7 ERROR:  syntax error at or near \"=\"\nLINE 3: ...             INNER JOIN carts c ON c.id = c_p.cart_id = c.id\n                                                                 ^","File":"\/var\/www\/html\/app\/Repository\/ProductRepository.php","Line":22}

An error occurred while processing the request: 
{"Message":"SQLSTATE[08P01]: <<Unknown error>>: 7 ERROR:  bind message supplies 1 parameters, but prepared statement \"pdo_stmt_00000001\" requires 0","File":"\/var\/www\/html\/app\/Repository\/ProductRepository.php","Line":28}

An error occurred while processing the request: 
{"Message":"SQLSTATE[08P01]: <<Unknown error>>: 7 ERROR:  bind message supplies 0 parameters, but prepared statement \"pdo_stmt_00000001\" requires 1","File":"\/var\/www\/html\/app\/Repository\/ProductRepository.php","Line":22}

An error occurred while processing the request: 
{"Message":"banana\\Entity\\Product::__construct(): Argument #1 ($name) must be of type string, null given, called in \/var\/www\/html\/app\/Repository\/ProductRepository.php on line 37","File":"\/var\/www\/html\/app\/Entity\/Product.php","Line":14}

