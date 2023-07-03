<?php

class Router {
    private $routes = [];

    public function addRoute(string $method, string $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function handleRequest() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $_SERVER['PATH_INFO'];

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestPath)) {

                if (is_callable($route['handler'])) {
                    echo call_user_func($route['handler']);
                    return;
                }

                return;
            }
        }

        http_response_code(404);
        echo json_encode(array('message' => 'NOT FOUND'));
    }

    private function matchPath(string $routePath, $requestPath) {
        $routePathParts = explode('/', trim($routePath, '/'));
        $requestPathParts = explode('/', trim($requestPath, '/'));
        if (count($routePathParts) !== count($requestPathParts)) {
            return false;
        }

        for ($i = 0; $i < count($routePathParts); $i++) {
            if ($routePathParts[$i] !== $requestPathParts[$i] && !preg_match('/{.*}/', $routePathParts[$i])) {
                return false;
            }
        }

        return true;
    }
}