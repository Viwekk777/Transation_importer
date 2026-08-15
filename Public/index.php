<?php

declare(strict_types=1);

use App\Container;
use App\Controllers\HomeController;
use App\Controllers\Router;
use App\Exceptions\RouteNotFoundException;
use App\Models\Db;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// 1. Build container and register services
$container = new Container();

$container->set(Db::class, fn () => new Db(
    dsn: "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    username: $_ENV['DB_USER'],
    password: $_ENV['DB_PASS'],
));

// 2. Initialize Router with Container
$router = new Router($container);

// 3. Define Routes
$router->registerRoutes('GET', '/', [HomeController::class, 'index']);
$router->registerRoutes('POST', '/', [HomeController::class, 'validateFile']);
$router->registerRoutes('GET', '/transaction', [HomeController::class, 'transaction']);

// 4. Resolve Request with query string safety & 404 exception handling
try {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    $method = $_SERVER['REQUEST_METHOD'];

    $router->resolve($method, $uri);
} catch (RouteNotFoundException) {
    http_response_code(404);
    echo '404 Not Found';
}