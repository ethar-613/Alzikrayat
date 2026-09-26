<?php
/**
 * Wraps PHP's session functions (login state, flash messages, etc).
 */


// this class it is a wrapper around PHP's session functions, with more secure cookie settings enabled and flash message system added
class Session
{
    private static $started = false;

    // Starts the session once per request, with a couple of safer cookie settings
    // it check if the session already started or not if from this class or outside, if true then return True
    public static function start()
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        session_set_cookie_params(array(
            'httponly' => true,           //this pervent js from reading session cookie  with document.cookie - so it protect from session thief with XSS
            'secure' => $isHttps,         //to make cookie sent only with https if only the connection is really https 
            'samesite' => 'Lax',          // to protect extra from CSRF attack
            'path' => '/',
        ));
        session_start();
        self::$started = true;
    }

    // this func to get/read value from session  , and if it not found return the default value
    // pass the key for the value wanted and default value if it not found
    // return the needed value 
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    // this func to set/write value in the session
    // pass the key as the name key foe the value, and the value want to assign it with the key name
    // return nothing it just add value to key name
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // this func to remove/delete value from session 
    // pass the key name for the value wanted to remove
    // return nothing it just remove value
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    // "Flash" data only lives until the next time it's read (used for messages)
    // passing key name and value it will store it temperory data tht use for only one time
    // not return anything 
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    // this return the value saved as flash before from its key name 
    // passing a key name and the default value 
    // it will save the value it got from the key in var and unset the var 
    public static function consumeFlash($key, $default = null)
    {
        $value = isset($_SESSION['_flash'][$key]) ? $_SESSION['_flash'][$key] : $default;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    // this generate new id for the session and with delete the old session  - this for securty from Fixation Attack
    // it use when login
    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    // this do clean destory for the session
    // make the $_SESSION empty (deleting from server), Deleting the session cookkie from the browser
    // destroy it and set started to false
    // used for Logout
    public static function destroy()
    {
        $_SESSION = array();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path']);
        }

        session_destroy();
        self::$started = false;
    }
}
