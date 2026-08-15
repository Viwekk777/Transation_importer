<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Exceptions\RouteNotFoundException;
use App\Container;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function registerRoutes(string $requestMethod, string $route, callable|array $action): void
    {
        $this->routes[$requestMethod][$route] = $action;
    }

    public function resolve(string $requestMethod, string $route): mixed
    {
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (!$action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            $class = $action[0] ?? null;
            $method = $action[1] ?? null;

            if ($class && class_exists($class)) {
                $object = $this->container->get($class);

                if (method_exists($object, $method)) {
                    return call_user_func_array([$object, $method], []);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}