<?php
/**
 * Basic app configuration and paths
  * Database settings can come from environment variables, but they default
  * to the usual XAMPP local values so the project runs out of the box
 */
 
 

define('APP_NAME', 'Alzikrayat');
define('APP_ROOT', dirname(__DIR__));
define('PUBLIC_PATH', APP_ROOT . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/images/uploads');
define('MAX_UPLOAD_BYTES', 8 * 1024 * 1024); // 8 MB

define('DB_HOST', getenv('ALZIKRAYAT_DB_HOST') ? getenv('ALZIKRAYAT_DB_HOST') : '127.0.0.1');
define('DB_NAME', getenv('ALZIKRAYAT_DB_NAME') ? getenv('ALZIKRAYAT_DB_NAME') : 'alzikrayat');
define('DB_USER', getenv('ALZIKRAYAT_DB_USER') ? getenv('ALZIKRAYAT_DB_USER') : 'root');
define('DB_PASSWORD', getenv('ALZIKRAYAT_DB_PASSWORD') ? getenv('ALZIKRAYAT_DB_PASSWORD') : '');

// Works out the URL prefix the app is running under (e.g. /alzikrayat/public)
function appBasePath()
{
    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php';
    $directory = dirname($scriptName);

    return ($directory === '/' || $directory === '.') ? '' : rtrim($directory, '/');
}
