<?php

namespace banana\Entity;

class CartProducts
{
    private Product $product;
    private Cart $cart;
    private int $quantity;


    public function __construct(Product $product)
    {
        $this->product = $product;
    }


    public function getProducts(): Product
    {
        return $this->product;
    }


    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }


    public function getCart(): Cart
    {
        return $this->cart;
    }


    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }


    public function getQuantity(): int
    {
        return $this->quantity;
    }
}