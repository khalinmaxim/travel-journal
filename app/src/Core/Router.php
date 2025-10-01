<?php
namespace App\Core;

final class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $pattern, callable|array $handler): void {
        $this->add('GET', $pattern, $handler);
    }
    public function post(string $pattern, callable|array $handler): void {
        $this->add('POST', $pattern, $handler);
    }
    private function add(string $method, string $pattern, $handler): void {
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        $this->routes[$method][] = [$regex, $handler];
    }

    public function dispatch(string $method, string $path): void {
        foreach ($this->routes[$method] ?? [] as [$regex, $handler]) {
            if (preg_match($regex, $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->invoke($handler, $params); // без return
                return; // просто выходим
            }
        }
        http_response_code(404);
        echo 'Not Found';
    }

    private function invoke($handler, array $params): void {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $obj = new $class;
            $obj->$method($params);
        } else {
            $handler($params);
        }
    }
}
