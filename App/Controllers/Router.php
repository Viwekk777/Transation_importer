<?php
declare(strict_types= 1);
namespace App\Controllers;
use App\Exceptions\RouteNotFoundException;
use App\Container;

class Router
{
    private array $routes = [];
    public function registerRoutes(string $requestMethod, string $route, callable|array $action): void
    {
        $this->routes[$requestMethod][$route] = $action;
        
    }
   /* public function get( string $route, callable|array $action): void
    {
        $this->RegisterRoutes("GET",$route, $action);
    }

    public function post( string $route, callable|array $action): void
    {
        $this->RegisterRoutes("POST",$route, $action);
    }
*/

    public function resolve(string $requestMethod,string $route) :mixed  
    {
        $route = str_replace("","", $route);
        $action = $this->routes[$requestMethod][$route] ?? null;
        if (! $action)
        {
           throw new RouteNotFoundException();
        }
        if(is_callable($action))
        {
            return call_user_func($action);
        }
        else if(is_array($action))
            {
                $class = $action[0] ?? null;
                $method = $action[1] ?? null;
                if(class_exists($class))
                    {
                        $object = new Container()->get($class) ;
                        if (method_exists($object, $method))
                            {
                                return call_user_func_array([$object, $method], []);
                            }
                      

                    }

                

            }
            throw new RouteNotFoundException();





        
    }




}


