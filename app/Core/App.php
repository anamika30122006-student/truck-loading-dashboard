<?php
namespace App\Core;

class App {
    private $routes = [];

    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function run() {
        Session::start();
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Normalize uri (remove subfolder if running in subdirectory)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '//') $uri = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchRoute($route['path'], $uri, $params)) {
                $handler = $route['handler'];
                if (is_array($handler)) {
                    $controllerClass = $handler[0];
                    $action = $handler[1];
                    $controller = new $controllerClass();
                    return call_user_func_array([$controller, $action], $params);
                } elseif (is_callable($handler)) {
                    return call_user_func_array($handler, $params);
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'><h1>404 - Page Not Found</h1><p>The requested page <code>" . htmlspecialchars($uri) . "</code> does not exist.</p><a href='/' style='color:#2563EB;'>Return to Dashboard</a></div>";
    }

    private function matchRoute($routePath, $uri, &$params) {
        $params = [];
        $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';

        if (preg_match($routePattern, $uri, $matches)) {
            foreach ($matches as $key => $val) {
                if (is_string($key)) {
                    $params[$key] = $val;
                }
            }
            return true;
        }
        return false;
    }
}
