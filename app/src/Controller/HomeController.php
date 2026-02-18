<?php

declare(strict_types=1);

namespace App\Controller;

use App\Config;
use App\View;

final class HomeController
{
    public function index(): void
    {
        $appName = Config::get('APP_NAME', 'Cloudflared Control Panel');
        View::render('home', [
            'appName' => $appName,
        ]);
    }
}
