<?php
declare(strict_types=1);

// Старт сессии здесь
session_start();

// PSR-0/очень простой автозагрузчик под пространство имён App\
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $file = $base_dir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) require $file;
});

use App\Core\{Config, Router};

// Инициализация конфигурации/БД
Config::init([
    'db' => [
        'host' => getenv('DB_HOST') ?: 'db',
        'port' => getenv('DB_PORT') ?: '5432',
        'name' => getenv('DB_NAME') ?: 'travel_journal',
        'user' => getenv('DB_USER') ?: 'postgres',
        'pass' => getenv('DB_PASS') ?: 'postgres',
    ],
    'base_url' => '/',
]);

$router = new Router();

// Маршруты
$router->get('/', [App\Controllers\HomeController::class, 'index']);

$router->get('/login', [App\Controllers\AuthController::class, 'loginForm']);
$router->post('/login', [App\Controllers\AuthController::class, 'login']);
$router->get('/register', [App\Controllers\AuthController::class, 'registerForm']);
$router->post('/register', [App\Controllers\AuthController::class, 'register']);
$router->post('/logout', [App\Controllers\AuthController::class, 'logout']);

$router->get('/trips', [App\Controllers\TripsController::class, 'index']);
$router->get('/trips/create', [App\Controllers\TripsController::class, 'createForm']);
$router->post('/trips', [App\Controllers\TripsController::class, 'store']);
$router->get('/trips/{id}', [App\Controllers\TripsController::class, 'show']);

// Запуск
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
