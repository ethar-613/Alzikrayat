<?php
/**
 * A couple of small static helpers for common HTTP responses.
 */


// this class have static functions to unify commom response patterns rather than repeating it every time needed
class Response
{
    // this used in situations like when user try to do action not  his own , he not allowed, like tag pr delete photo 
    // it just print message as text
    // passing message to it as argument or this will show as default message. and stop the execution
    public static function forbidden($message = 'You are not allowed to perform this action.')
    {
        http_response_code(403);
        exit($message);
    }

    // This for when theres no route matchs
    // it show view error page in that page it show the error message 
    // can pass message that will appear at that page or will show default message 
    public static function notFound($message = 'The page you are looking for could not be found.')
    {
        http_response_code(404);
        require APP_ROOT . '/views/errors/404.php';
        exit;
    }
}
