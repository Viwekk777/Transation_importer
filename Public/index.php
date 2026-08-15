<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Container;
use App\Controllers\Router;
use App\Models\Db;
use App\Controllers\HomeController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// 1. Build the container and bind Db (it needs .env values, so it can't auto-wire itself)
$container = new Container();

$container->set(Db::class, function ($c) {
    return new Db(
        dsn: "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        username: $_ENV['DB_USER'],
        password: $_ENV['DB_PASS'],
    );
});

// 2. Router gets the container — no more manually `new`-ing controllers
$router = new Router($container);

$router->registerRoutes('GET', '/', [HomeController::class, 'index']);
$router->registerRoutes('POST', '/', [HomeController::class, 'validateFile']);
$router->registerRoutes('GET', '/transaction', [HomeController::class, 'transaction']);

$router->resolve(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);