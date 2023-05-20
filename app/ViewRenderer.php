<?php

namespace banana;

class ViewRenderer
{
    public function render(string $view, array $params, bool $isLayout): ?string
    {
        extract($params);

        ob_start();

        require_once $view;

        if ($isLayout) {

            $start = ob_get_clean();

            $content = file_get_contents('../Views/layout.html');

            return str_replace('{content}', $start, $content);
        }

        return null;
    }
}