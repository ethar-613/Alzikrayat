<?php
/**
 * Small helper functions used from the views and controllers.
 * Not in a class to be global functions.
 */


// Escape a value before printing it into HTML (prevents XSS)
// used it on views for every display so it protect from XSS injection 
// so if there any script  the browser will not execute it but only display it as normal text
// first forcefully convert the value to string to prevent any errors if passed null or number , 
// ten it escape the single and double quotos by ENT_QUOTES  ,
//  UTF-8 to explicit encoding specification
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}


// Builds a full app URL, so links still work if the project lives in a subfolder
// used it in all the views rather than writing static urls
// it uses appBasePath() that on app.php to get the base path for the project /url prefix 
// then cleaning the path and combine them then return the result.
function url($path = '/')
{
    $basePath = appBasePath();
    $cleanPath = '/' . ltrim($path, '/');

    if ($cleanPath === '/') {
        return $basePath === '' ? '/' : $basePath . '/';
    }

    return $basePath . $cleanPath;
}

// THis is for the assests paths like css and js 
function asset($path)
{
    return url('/assets/' . ltrim($path, '/'));
}

// pass a file/photo name to the function 
// then it return the url for the photo wanted using url() method
// 
function uploadUrl($fileName)
{
    //basename() using to take only its name and ignore any path before it. and rawurlencode() to encode the file name securly to use it in url
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
// the token will be created only once for every session 
// this to protect from CSRF attacks 
function csrfToken()
{
    $token = Session::get('csrf_token');

    if (!is_string($token) || $token === '') {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }

    return $token;
}

// this to generate html field used in every post field 
// the field will not display to user but it sill be send with the form input data 
function csrfField()
{
    return '<input type="hidden" name="_token" value="' . e(csrfToken()) . '">';
}

// called in every start of controller function that recieve POST 
// it compare the token recived with the one at the session 
// it will throw 419 error if it didnt match and stop execution 
// 
function verifyCsrf()
{
    $submittedToken = isset($_POST['_token']) ? $_POST['_token'] : '';
    $knownToken = Session::get('csrf_token');

    if (!is_string($knownToken) || !hash_equals($knownToken, $submittedToken)) {
        http_response_code(419); //419 not standard code status
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
