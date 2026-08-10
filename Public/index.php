<?php
declare(strict_types= 1);

require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\Router;
use App\Controllers\HomeController;




$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$router = new Router();
$router->registerRoutes('GET','/',[HomeController::class,'index']);
$router->registerRoutes('POST','/',[HomeController::class,'transaction']);

$router->Resolve(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

