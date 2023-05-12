<?php

namespace banana;

use banana\Exception\ExceptionContainer;

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
            if (class_exists($name)) {
                return new $name();
            }

            throw new ExceptionContainer("Sorry error with container, invalid class name {$name}");
        }

        return $this->services[$name]($this);
    }
}