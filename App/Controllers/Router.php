<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Container;
use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    public function registerRoutes(string $requestMethod, string $route, callable|array $action): void
    {
        $this->routes[strtoupper($requestMethod)][$route] = $action;
    }

    public function get(string $route, callable|array $action): void
    {
        $this->registerRoutes('GET', $route, $action);
    }

    public function post(string $route, callable|array $action): void
    {
        $this->registerRoutes('POST', $route, $action);
    }

    public function resolve(string $requestMethod, string $route): mixed
    {
        $path = parse_url($route, PHP_URL_PATH) ?? '/';

        // Automatically strip XAMPP subfolder paths (e.g. /Transaction_importer/Public)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
            $path = substr($path, strlen($scriptDir));
        }

        // Ensure leading slash and normalize route path
        $path = '/' . trim($path, '/');
        $method = strtoupper($requestMethod);

        $action = $this->routes[$method][$path] ?? null;

        if (!$action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $methodName] = $action;

            if ($class && class_exists($class)) {
                $object = $this->container->get($class);

                if (method_exists($object, $methodName)) {
                    return call_user_func_array([$object, $methodName], []);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}