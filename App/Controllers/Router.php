<?php

declare(strict_types=1);
namespace App\Controllers;
//require __DIR__ . '/../Exceptions/RouteNotFoundException.php';
use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes = [];
    public function get(string $path, callable|array $action): void
    {
        $this->routes['GET'][$path] = $action;
    }
    public function post(string $path, callable|array $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public  function dispatch(string $requestURI , string $baseURI, string $requestMethod):void
    {
        $path = Parse_url($requestURI,PHP_URL_PATH);
        if(str_starts_with($path,$baseURI))
        {
            $path=substr($path, strlen($baseURI));

        }


        if(isset(
            $this->routes[$requestMethod][$path]
        )){$action = $this->routes[$requestMethod][$path];

        if (is_array($action))
        {
            $Class = $action[0];
            $Method = $action[1];


                $Class = new $Class();
                $Class->$Method();







        }
        else
        {
            $action();

        }}
        else
        {
            throw new RouteNotFoundException();
        }





    }
}
