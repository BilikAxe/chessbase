<?php

namespace banana;

class Container
{
    private array $obj = [];

    public function set(string $name, callable $callback): void
    {
        $this->obj[$name] = $callback;
    }

    public function get(string $name): callable
    {
        return $this->obj[$name];
    }
}