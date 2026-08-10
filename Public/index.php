<?php
declare(strict_types= 1);

require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\Router;
use App\Controllers\HomeController;
use App\Validators\FileValidator;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$router = new Router();
$router->registerRoutes('GET','/',[HomeController::class,'index']);
$router->registerRoutes('POST','/',[FileValidator::class,'FileValidaor']);


$router->Resolve(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);


