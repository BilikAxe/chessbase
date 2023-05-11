<?php

namespace banana;

class Container
{
    private array $obj = [];

    public function set(array $obj): void
    {
        $this->obj = $obj;
    }

    public function get(): array
    {
        return $this->obj;
    }
}