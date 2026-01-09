<?php

class Router
{
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    public function get(string $uri, array $action, array $middleware = []): void
    {
        $this->routes['GET'][$this->normalize($uri)] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function post(string $uri, array $action, array $middleware = []): void
    {
        $this->routes['POST'][$this->normalize($uri)] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // REMOVE PROJECT FOLDER FROM URI
        $basePath = '/buy-match';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = $this->normalize($uri);

        if (isset($this->routes[$method][$uri])) {
            $this->runRoute($this->routes[$method][$uri]);
            return;
        }

        foreach ($this->routes[$method] as $route => $data) {
            if ($this->match($route, $uri, $params)) {
                $this->runRoute($data, $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    private function runRoute(array $data, array $params = []): void
    {
        if (!empty($data['middleware'])) {
            foreach ($data['middleware'] as $mw) {
                $this->runMiddleware($mw);
            }
        }

        [$controller, $method] = $data['action'];
        require_once __DIR__ . "/Controllers/{$controller}.php";
        $instance = new $controller();
        call_user_func_array([$instance, $method], $params);
    }

    private function runMiddleware(string $mw): void
    {
        [$name, $param] = array_pad(explode(':', $mw, 2), 2, null);

        switch ($name) {
            case 'auth':
                if (empty($_SESSION['user_id'])) {
                    header('Location: /buy-match/login');
                    exit;
                }
                break;

            case 'role':
                if (empty($_SESSION['user_id'])) {
                    http_response_code(403);
                    echo "403 - Forbidden";
                    exit;
                }

                $userRole = $_SESSION['user_role'];
                $allowedRoles = array_map('trim', explode(',', $param));

                if (!in_array($userRole, $allowedRoles)) {
                    http_response_code(403);
                    echo "403 - Forbidden";
                    exit;
                }
                break;

            default:
                throw new Exception("Unknown middleware: {$name}");
        }
    }

    private function match(string $route, string $uri, &$params = []): bool
    {
        $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
        $pattern = "#^{$pattern}$#";

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            $params = $matches;
            return true;
        }

        return false;
    }

    private function normalize(string $uri): string
    {
        return rtrim($uri, '/') ?: '/';
    }
}
