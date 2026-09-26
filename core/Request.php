<?php
/**
 * Tiny helper class that wraps the PHP superglobals ($_GET, $_POST, $_SERVER)
 * so controllers don't have to touch them directly everywhere
 */

// class that have the main functions that it wrap the superglobals in static functions. that hundle requests data 
class Request
{
    
    // this Return th request method (GET , POST,...) with uppercase for all
    // isset used just in case maybe $_SERVER['REQUEST_METHOD'] not exist so it will return GET as default value
    // need no argument
    public static function method()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
    }

    // This func to return the request path
    // first it get the pure url with every query string
    // then cut it to get only the Path part and leave other parts
    // then return the path with checking it to not be null 
    // need no argument
    public static function path()
    {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $path = parse_url($requestUri, PHP_URL_PATH);

        return is_string($path) && $path !== '' ? $path : '/';
    }

    // This using to read the data sent with POST 
    // pass argument the key name/colum name and the Default value if value not found
    // Returning the value sent in that name 
    public static function input($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    // This usebto read url parameters/query string with GET
    // pass argument the parameter name and the Default value if value not found
    // Returning the value it found or default
    public static function query($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
}
