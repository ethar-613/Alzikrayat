<?php
/**
 * A couple of small static helpers for common HTTP responses.
 */

class Response
{
    public static function redirect($location)
    {
        header('Location: ' . $location);
        exit;
    }

    public static function forbidden($message = 'You are not allowed to perform this action.')
    {
        http_response_code(403);
        exit($message);
    }

    public static function notFound($message = 'The page you are looking for could not be found.')
    {
        http_response_code(404);
        require APP_ROOT . '/views/errors/404.php';
        exit;
    }
}
