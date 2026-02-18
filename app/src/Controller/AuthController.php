<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth;
use App\Config;
use App\Database;
use App\View;

final class AuthController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            Auth::redirect('/services');
        }

        View::render('login', [
            'appName' => Config::get('APP_NAME', 'Control Panel'),
            'error' => Auth::flash('error'),
        ]);
    }

    public function login(): void
    {
        if (!Auth::validateCsrf($_POST['csrf_token'] ?? null)) {
            Auth::flash('error', 'Invalid session token.');
            Auth::redirect('/login');
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            Auth::flash('error', 'Email and password are required.');
            Auth::redirect('/login');
        }

        $stmt = Database::pdo()->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Auth::flash('error', 'Invalid credentials.');
            Auth::redirect('/login');
        }

        Auth::login((int)$user['id']);
        Auth::redirect('/services');
    }

    public function logout(): void
    {
        Auth::logout();
        Auth::redirect('/login');
    }
}
