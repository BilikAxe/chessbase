<?php

namespace banana\Entity;

class CartProduct
{
    private Product $product;
    private Cart $cart;
    private int $quantity;


    public function __construct(Product $product, Cart $cart, int $quantity)
    {
        $this->product = $product;
        $this->cart = $cart;
        $this->quantity = $quantity;
    }


    public function getProduct(): Product
    {
        return $this->product;
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