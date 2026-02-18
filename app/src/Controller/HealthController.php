<?php

declare(strict_types=1);

namespace App\Controller;

final class HealthController
{
    public function check(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'ok',
            'time' => gmdate('c'),
        ], JSON_UNESCAPED_SLASHES);
    }
}
