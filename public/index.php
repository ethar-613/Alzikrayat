<?php
/**
 * Front controller, every request comes through this one file first.
 */

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

// Autoload classes: look for core/, models/ and controllers/ folders
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

require_once APP_ROOT . '/core/helpers.php';
Session::start();
require_once APP_ROOT . '/routes/web.php';

try {
    $router->dispatch(Request::method(), Request::path());
} catch (Exception $exception) {
    error_log($exception->getMessage());
    http_response_code(500);

    $isDevelopment = getenv('APP_ENV') !== 'production';
    $errorDetail = $isDevelopment ? $exception->getMessage() : 'Please try again later.';
    require APP_ROOT . '/views/errors/500.php';
}
