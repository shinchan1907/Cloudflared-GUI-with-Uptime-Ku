<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth;
use App\Config;
use App\View;

final class SettingsController
{
    public function index(): void
    {
        Auth::requireLogin();
        
        View::render('settings', [
            'appName' => Config::get('APP_NAME', 'Control Panel'),
            'cfEmail' => Config::get('CF_EMAIL', ''),
            'cfToken' => Config::get('CF_API_TOKEN', ''),
            'cfZone' => Config::get('CF_ZONE_ID', ''),
            'cfAccount' => Config::get('CF_ACCOUNT_ID', ''),
            'primaryDomain' => Config::get('PRIMARY_DOMAIN', ''),
            'message' => Auth::flash('message'),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();
        
        // In a real app, we would save these to the database.
        // For this prototype, we'll just show that we received them.
        Auth::flash('message', 'Settings updated (Prototype: changes not persisted to .env file).');
        Auth::redirect('/settings');
    }
}
