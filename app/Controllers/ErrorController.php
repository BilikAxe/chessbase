<?php

namespace banana\Controllers;

use banana\ViewRenderer;

class ErrorController
{
    private ViewRenderer $renderer;


    public function __construct(ViewRenderer $renderer)
    {
        $this->renderer = $renderer;
    }


    public function error(): ?string
    {
        return $this->renderer->render(
            '../Views/error404.html',
            [],
            false
        );
    }
}