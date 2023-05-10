<?php

namespace banana\Controllers;

class ErrorController
{
    public function error(): array
    {
        return [
            './views/error.html',
            [],
            false
        ];
    }
}