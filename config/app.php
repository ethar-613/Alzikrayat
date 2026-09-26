<?php
/**
 * Basic app configuration and paths
 * Database settings can come from environment variables, but they default to the usual XAMPP local values so the project runs out of the box
 * الملف ده مسؤول عن الاعدادات العامة وثوابت حأحتاجها في المشروع كله
 * فيه بيانات الاتصال مع قاعدة البيانات لكن ما ليه علاقة مباشرة بيه
 */


//this contain the application name so i can put it whenever i need to write the app name. so when i need to change the app name i will change it only here
define('APP_NAME', 'Alzikrayat');
//ده يتخزن فيه مسار المجلد الموجود فيه الملف ده - يعني مسار جذر المشروع كله واللي حأستخدمه عشان ابني اي مسار تاني داخل المشروع
define('APP_ROOT', dirname(__DIR__));
// here built /public path from 'APP_ROOT' constraint so when app path change it will update automatically
define('PUBLIC_PATH', APP_ROOT . '/public');
// this is the same thing as 'PUBLIC_PATH'. but for photo uploads path. i use it on PhotoController to save the uploaded photos
define('UPLOAD_PATH', PUBLIC_PATH . '/images/uploads');
// this is the max size of the uploaded photos with bytes. i put it here so i can change it from one place in future i wanted to change it.
define('MAX_UPLOAD_BYTES', 8 * 1024 * 1024);

//this four is the database connection data. host - database name - username - password
// it will first trying to read value from env variables and if it didnt found it it will use the default values. for me its XAMPP data
define('DB_HOST', getenv('ALZIKRAYAT_DB_HOST') ? getenv('ALZIKRAYAT_DB_HOST') : 'localhost');
define('DB_NAME', getenv('ALZIKRAYAT_DB_NAME') ? getenv('ALZIKRAYAT_DB_NAME') : 'alzikrayat');
define('DB_USER', getenv('ALZIKRAYAT_DB_USER') ? getenv('ALZIKRAYAT_DB_USER') : 'root');
define('DB_PASSWORD', getenv('ALZIKRAYAT_DB_PASSWORD') ? getenv('ALZIKRAYAT_DB_PASSWORD') : '');

//this function discover the base path/URL prefix that the project running under
//used by url() function on helpers.php and router.php so all lnks can work probably regardless where the project work in
function appBasePath()
{
    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/index.php';
    $directory = dirname($scriptName);

    return ($directory === '/' || $directory === '.') ? '' : rtrim($directory, '/');
}
