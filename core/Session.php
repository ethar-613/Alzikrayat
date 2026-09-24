<?php
/**
 * Wraps PHP's session functions (login state, flash messages, etc).
 */

class Session
{
    private static $started = false;

    // Starts the session once per request, with a couple of safer cookie settings
    public static function start()
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        session_set_cookie_params(array(
            'httponly' => true,
            'secure' => $isHttps,
            'samesite' => 'Lax',
            'path' => '/',
        ));
        session_start();
        self::$started = true;
    }

    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    // "Flash" data only lives until the next time it's read (used for messages)
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function consumeFlash($key, $default = null)
    {
        $value = isset($_SESSION['_flash'][$key]) ? $_SESSION['_flash'][$key] : $default;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

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
