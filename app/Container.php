<?php

namespace banana;

class Container
{
    private array $services = [];

    public function set(string $name, callable $callback): void
    {
        $this->services[$name] = $callback;
    }

    public function get(string $name): object
    {
        if (!isset($this->services[$name]))
        {
            return new $name();
        }

        return $this->services[$name]();
    }
}