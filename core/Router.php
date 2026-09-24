<?php
/**
 * The manual router 
 * Routes are registered with add() and matched against the request path
 * using regular expressions, the same way it's shown in the course slides:
 * every "{param}" in a route path becomes a regex group that captures whatever value is in the URL.
 */

class Router
{
    private $routes = [];

    // Register a route: method, path pattern, [ControllerClass, methodName]
    public function add($method, $path, $handler)
    {
        $this->routes[] = array(
            'method' => strtoupper($method),
            'path' => $path === '/' ? '/' : '/' . trim($path, '/'),
            'handler' => $handler,
        );
    }

    // Find a route that matches the current request and call its controller
    public function dispatch($method, $requestPath)
    {
        $path = $this->stripBasePath($requestPath);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Turn "/photo/{id}" into a regex like "#^/photo/([^/]+)$#"
            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // drop the full match, keep only the captured params

                list($controllerClass, $action) = $route['handler'];
                $controller = new $controllerClass();

                return call_user_func_array(array($controller, $action), $matches);
            }
        }

        // No route matched this method + path combination
        Response::notFound();
    }

    // Removes the /public part of the URL so routes work from a subfolder too
    private function stripBasePath($requestPath)
    {
        $basePath = appBasePath();

        if ($basePath !== '' && strpos($requestPath, $basePath) === 0) {
            $requestPath = substr($requestPath, strlen($basePath));
            if ($requestPath === '') {
                $requestPath = '/';
            }
        }

        $path = parse_url($requestPath, PHP_URL_PATH);
        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        return $path === '/' ? '/' : '/' . trim($path, '/');
    }
}
