<?php

declare(strict_types=1);

namespace App;

final class View
{
    public static function render(string $template, array $data = []): void
    {
        $templatePath = __DIR__ . '/../templates/' . $template . '.php';
        if (!file_exists($templatePath)) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo "Template not found";
            return;
        }

        $data['templatePath'] = $templatePath;
        extract($data, EXTR_SKIP);

        include __DIR__ . '/../templates/layout.php';
    }
}
