<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth;
use App\Config;
use App\Service\ServiceRepository;
use App\View;

final class ServiceController
{
    private ServiceRepository $repo;

    public function __construct()
    {
        $this->repo = new ServiceRepository();
    }

    public function index(): void
    {
        Auth::requireLogin();
        $services = $this->repo->all();

        View::render('services/index', [
            'appName' => Config::get('APP_NAME', 'Control Panel'),
            'services' => $services,
            'message' => Auth::flash('message'),
        ]);
    }

    public function create(): void
    {
        Auth::requireLogin();
        View::render('services/new', [
            'appName' => Config::get('APP_NAME', 'Control Panel'),
            'primaryDomain' => Config::get('PRIMARY_DOMAIN', 'example.com'),
            'error' => Auth::flash('error'),
        ]);
    }

    public function store(): void
    {
        Auth::requireLogin();

        if (!Auth::validateCsrf($_POST['csrf_token'] ?? null)) {
            Auth::flash('error', 'Invalid session token.');
            Auth::redirect('/services/new');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $type = (string)($_POST['domain_type'] ?? 'subdomain');
        $subdomain = trim((string)($_POST['subdomain'] ?? ''));
        $customDomain = trim((string)($_POST['custom_domain'] ?? ''));
        $localPort = (int)($_POST['local_port'] ?? 0);
        $protocol = trim((string)($_POST['protocol'] ?? 'http'));

        if ($name === '' || $localPort <= 0) {
            Auth::flash('error', 'Name and local port are required.');
            Auth::redirect('/services/new');
        }

        $isCustom = 0;
        $finalSubdomain = null;
        $finalDomain = null;

        if ($type === 'subdomain') {
            if ($subdomain === '') {
                Auth::flash('error', 'Subdomain is required for subdomain mapping.');
                Auth::redirect('/services/new');
            }
            $finalSubdomain = $subdomain;
            $finalDomain = Config::get('PRIMARY_DOMAIN', 'example.com');
        } else {
            if ($customDomain === '') {
                Auth::flash('error', 'Custom domain is required.');
                Auth::redirect('/services/new');
            }
            $isCustom = 1;
            $finalDomain = $customDomain;
        }

        if (!in_array($protocol, ['http', 'https'], true)) {
            $protocol = 'http';
        }

        $id = $this->repo->create($name, $finalSubdomain, $finalDomain, $isCustom, $localPort, $protocol);
        Auth::flash('message', 'Service created. Follow the setup steps below.');
        Auth::redirect('/services/setup?id=' . $id);
    }

    public function setup(): void
    {
        Auth::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $service = $this->repo->find($id);

        if (!$service) {
            Auth::flash('message', 'Service not found.');
            Auth::redirect('/services');
        }

        View::render('services/setup', [
            'appName' => Config::get('APP_NAME', 'Control Panel'),
            'primaryDomain' => Config::get('PRIMARY_DOMAIN', 'example.com'),
            'service' => $service,
        ]);
    }

    public function toggle(): void
    {
        Auth::requireLogin();

        if (!Auth::validateCsrf($_POST['csrf_token'] ?? null)) {
            Auth::flash('message', 'Invalid session token.');
            Auth::redirect('/services');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->repo->toggle($id);
        }

        Auth::redirect('/services');
    }
}
