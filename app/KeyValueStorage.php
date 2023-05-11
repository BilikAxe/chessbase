<?php

namespace banana;

class KeyValueStorage
{
    private array $storage = [
        'key' => 'value';
    ];


    public function set(string $key, mixed $value): void
    {
        $this->storage[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->storage[$key];
    }
}