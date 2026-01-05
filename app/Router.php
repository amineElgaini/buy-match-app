<?php

class Router
{
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    /**
     * Register GET route
     */
    public function get(string $uri, array $action, array $middleware = []): void
    {
        $this->routes['GET'][$this->normalize($uri)] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    /**
     * Register POST route
     */
    public function post(string $uri, array $action, array $middleware = []): void
    {
        $this->routes['POST'][$this->normalize($uri)] = [
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    /**
     * Dispatch request
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->normalize(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        // Exact match first
        if (isset($this->routes[$method][$uri])) {
            $this->runRoute($this->routes[$method][$uri]);
            return;
        }

        // Match routes with parameters
        foreach ($this->routes[$method] as $route => $data) {
            if ($this->match($route, $uri, $params)) {
                $this->runRoute($data, $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Run route: middleware + controller action
     */
    private function runRoute(array $data, array $params = []): void
    {
        // Run middleware
        if (!empty($data['middleware'])) {
            foreach ($data['middleware'] as $mw) {
                $this->runMiddleware($mw);
            }
        }

        // Run controller action
        [$controller, $method] = $data['action'];
        require_once __DIR__ . "/Controllers/{$controller}.php";
        $instance = new $controller();
        call_user_func_array([$instance, $method], $params);
    }

    /**
     * Middleware handler
     */
    private function runMiddleware(string $mw): void
    {
        [$name, $param] = array_pad(explode(':', $mw, 2), 2, null);

        switch ($name) {
            case 'auth':
                if (empty($_SESSION['user'])) {
                    header('Location: /login');
                    exit;
                }
                break;

            case 'role':
                if (empty($_SESSION['user'])) {
                    http_response_code(403);
                    echo "403 - Forbidden";
                    exit;
                }

                $userRole = $_SESSION['user']['role'];
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

    /**
     * Match route with parameters, e.g., /matches/{id}
     */
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

    /**
     * Normalize URI: remove trailing slash
     */
    private function normalize(string $uri): string
    {
        return rtrim($uri, '/') ?: '/';
    }
}
