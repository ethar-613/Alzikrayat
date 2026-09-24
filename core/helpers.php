<?php
/**
 * Small helper functions used from the views and controllers.
 */

// Escape a value before printing it into HTML (prevents XSS)
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Builds a full app URL, so links still work if the project lives in a subfolder
function url($path = '/')
{
    $basePath = appBasePath();
    $cleanPath = '/' . ltrim($path, '/');

    if ($cleanPath === '/') {
        return $basePath === '' ? '/' : $basePath . '/';
    }

    return $basePath . $cleanPath;
}

function asset($path)
{
    return url('/assets/' . ltrim($path, '/'));
}

function uploadUrl($fileName)
{
    return url('/images/uploads/' . rawurlencode(basename($fileName)));
}

// Redirects the browser to another page and stops the script
function redirect($path)
{
    if (strpos($path, '/') === 0) {
        $path = url($path);
    }
    header('Location: ' . $path);
    exit;
}

// A basic CSRF token so forms can't be submitted from another site
function csrfToken()
{
    $token = Session::get('csrf_token');

    if (!is_string($token) || $token === '') {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }

    return $token;
}

function csrfField()
{
    return '<input type="hidden" name="_token" value="' . e(csrfToken()) . '">';
}

function verifyCsrf()
{
    $submittedToken = isset($_POST['_token']) ? $_POST['_token'] : '';
    $knownToken = Session::get('csrf_token');

    if (!is_string($knownToken) || !hash_equals($knownToken, $submittedToken)) {
        http_response_code(419);
        exit('The form expired. Please go back and try again.');
    }
}

// Gets back a value the user typed before a validation error (so they don't retype it)
function old($key, $default = '')
{
    $oldValues = Session::get('old_input', array());
    $value = is_array($oldValues) && isset($oldValues[$key]) ? $oldValues[$key] : $default;

    return e($value);
}
