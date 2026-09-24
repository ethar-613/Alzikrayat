<?php
/**
 * Tiny helper class that wraps the PHP superglobals ($_GET, $_POST, ...)
 * so controllers don't have to touch them directly everywhere.
 */

class Request
{
    public static function method()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
    }

    public static function path()
    {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $path = parse_url($requestUri, PHP_URL_PATH);

        return is_string($path) && $path !== '' ? $path : '/';
    }

    public static function input($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    public static function query($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
}
