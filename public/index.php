<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


//require_once dirname(__DIR__) . '/App/Controllers/HomeController.php';
//require_once __DIR__ . '/../App/Controllers/Router.php';


$router = new App\Controllers\Router();
$router->get('/',[\App\Controllers\HomeController::class,'index']);
$router->get('/upload', [\App\Controllers\TransactionController::class, 'showForm']);
$router->post('/upload', [\App\Controllers\TransactionController::class, 'upload']);


$Whole_path= dirname($_SERVER['SCRIPT_NAME']);
$Whole_path= str_replace("\\", "/", $Whole_path);
$router->dispatch($_SERVER['REQUEST_URI'],$Whole_path,$_SERVER['REQUEST_METHOD']);

$options=[];
try {
    $database = new App\Database($_ENV['DB_HOST'], $_ENV['DB_DATABASE'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
}
catch (\PDOException $e)
{
    echo 'Database error in index ';
}
