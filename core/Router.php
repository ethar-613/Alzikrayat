<?php
/**
 * The manual router 
 * Routes are registered with add() and matched against the request path
 * using regular expressions, the same way it's shown in the course slides:
 * every "{param}" in a route path becomes a regex group that captures whatever value is in the URL.
 */


// it do store the registered routes , and when request came it match it using RegEx, 
// and call controller and the suit function.
class Router
{
    private $routes = []; //assosiative array.

    // Register a route: method, path pattern, [ControllerClass, methodName].
    // this function is to store/save all routes with thier info[method , path, controller and its wanted function].
    // pass three argument: $method is the HTTP request method like "GET", "POST" . $path is the URL path ,
    //     $handler is array contain the controller clas name with the appropriate function for this route.
    // it Do Not Return anything. its just to store the routes in assosiative array
    public function add($method, $path, $handler)
    {
        $this->routes[] = array(
            'method' => strtoupper($method), //strtouper used here to make method to be all uppercase/capital letters , بس لتفادي مشاكل حساسية الحروف
            'path' => $path === '/' ? '/' : '/' . trim($path, '/'), //this is normalization to the paths
            'handler' => $handler,
        );
    }

    // Find a route that matches the current request and call its controller
    // it clean the path that came from request by stripBasePath() function , and unify method letter case
    // then loop and take every registered route to match and skip every route that its method do not match the required route
    // if method match then turn the path into RegEx  , and use '#' to delimiter the path to ensure it will be for all the path not just part 
    // then check if there is a match or not using preg_match() method. it will check and put the result in $matches var
    // if it match then split $handler to controller class and function. 
    // then create object from controller class name. here will call the autoload automatically  to do require_once to contain the file
    //  then it return the show the result and stop the search
    // if routes done and no match it will call Response not found that view 404.php page
    // the argument is the request method and the path 
    // used at execution time
    public function dispatch($method, $requestPath)
    {
        $path = $this->stripBasePath($requestPath);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            // Turn "/photo/{id}" into a regex like "#^/photo/([^/]+)$#"
            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)',  $route['path']);
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
    // this function to help from project root problems. 
    // it uses for it appBasePath() from config/app.php 
    // it cut the addiitional part before the real path
    private function stripBasePath($requestPath)
    {
        $basePath = appBasePath();

        if ($basePath !== '' && strpos($requestPath, $basePath) === 0) {
            $requestPath = substr($requestPath, strlen($basePath));
            if ($requestPath === '') {
                $requestPath = '/';
            }
        }

        $path = parse_url($requestPath, PHP_URL_PATH); //this method take only path part from the url and ignore any query string or fragment
        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        return $path === '/' ? '/' : '/' . trim($path, '/'); // normalizaation to the path to be the way works with add exactly
    }
}
