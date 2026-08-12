<?php
declare(strict_types= 1);


require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\Router;
use App\Validators\FileValidator;
use App\Models\Db;
use App\Models\Transaction;
use App\Controllers\HomeController;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db = new Db(
    dsn: "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    username: $_ENV['DB_USER'],
    password: $_ENV['DB_PASS'],
);
$homeController = new HomeController($db);



$router = new Router();
$router->registerRoutes('GET','/',[HomeController::class,'index']);
$router->registerRoutes('POST','/',[HomeController::class,'validateFile']);
$router->registerRoutes('GET','/transaction',[HomeController::class,'transaction']);

$router->Resolve(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);





