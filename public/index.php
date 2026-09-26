<?php
/**
 * Front controller, every request comes through this one file first.
 */


//contain app configuration and database connection before anything.
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

// Autoload classes: look for core/, models/ and controllers/ folders
// it register a function that automatically called each time php need class not got yet by require
// do require_once for the first file matching the class name exactly , and return imediatly without check other directories
spl_autoload_register(function ($class) {
    $directories = array('core', 'models', 'controllers');

    foreach ($directories as $directory) {
        $file = APP_ROOT . '/' . $directory . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

// contain helper functions ,start Session ,contain web.php that contain the router object
require_once APP_ROOT . '/core/helpers.php';
Session::start();
require_once APP_ROOT . '/routes/web.php';

// this take request method and path and match it with the registered routes, then call appropriate controller
// --- Error Exceptions:
// then if any Exception happened within the execution it handled here so not display raw php error for user
// error_log - to logs the error details in the server log , not for end user
// then sent http code 500 for the browser , its internal server error 
// then it divide between devolopment env and production env so it not leak technical details to the end user. for app Security.
// it will display the error at 500.php page , display 'Please try again later' to end user in production env. and error technical detail in devolopment env.
try {
    $router->dispatch(Request::method(), Request::path());
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(500);

    $isDevelopment = getenv('APP_ENV') !== 'production'; 
    $errorDetail = $isDevelopment ? $exception->getMessage() : 'Please try again later.';
    require APP_ROOT . '/views/errors/500.php';
}
