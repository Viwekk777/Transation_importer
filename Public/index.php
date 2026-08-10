<?php
declare(strict_types= 1);

require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\Router;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
var_dump($_SERVER['REQUEST_URI']);
$router = new Router();

