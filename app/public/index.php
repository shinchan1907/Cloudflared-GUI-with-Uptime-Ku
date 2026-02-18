<?php

declare(strict_types=1);

require __DIR__ . '/../src/Config.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Router.php';
require __DIR__ . '/../src/View.php';
require __DIR__ . '/../src/Auth.php';
require __DIR__ . '/../src/Service/ServiceRepository.php';
require __DIR__ . '/../src/Service/CloudflareService.php';
require __DIR__ . '/../src/Controller/HomeController.php';
require __DIR__ . '/../src/Controller/HealthController.php';
require __DIR__ . '/../src/Controller/AuthController.php';
require __DIR__ . '/../src/Controller/ServiceController.php';
require __DIR__ . '/../src/Controller/SettingsController.php';
require __DIR__ . '/../src/Controller/AgentController.php';

use App\Auth;
use App\Config;
use App\Router;
use App\Controller\HomeController;
use App\Controller\HealthController;
use App\Controller\AuthController;
use App\Controller\ServiceController;
use App\Controller\SettingsController;
use App\Controller\AgentController;

$envPath = file_exists(__DIR__ . '/../.env') ? __DIR__ . '/../.env' : __DIR__ . '/../../.env';
Config::bootstrap($envPath);
Auth::bootstrap();

$router = new Router();
$router->get('/', [new HomeController(), 'index']);
$router->get('/health', [new HealthController(), 'check']);

$router->get('/login', [new AuthController(), 'showLogin']);
$router->post('/login', [new AuthController(), 'login']);
$router->post('/logout', [new AuthController(), 'logout']);

$router->get('/services', [new ServiceController(), 'index']);
$router->get('/services/new', [new ServiceController(), 'create']);
$router->get('/services/setup', [new ServiceController(), 'setup']);
$router->post('/services', [new ServiceController(), 'store']);
$router->post('/services/toggle', [new ServiceController(), 'toggle']);

$router->get('/settings', [new SettingsController(), 'index']);
$router->post('/settings', [new SettingsController(), 'store']);

$router->get('/agent/install', [new AgentController(), 'install']);

$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
