<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            die('404 | Route Not Found');
        }

        [$controller, $action] = $this->routes[$method][$uri];

        call_user_func([new $controller, $action]);
    }
}
