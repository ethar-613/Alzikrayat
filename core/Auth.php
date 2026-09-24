<?php
/** 
 * Keeps track of who is logged in, using the session
 * Only a few non-sensitive fields (id, name, email) are kept in the session. the password hash is never stored here
 */

class Auth
{
    public static function user()
    {
        $user = Session::get('auth_user');

        return is_array($user) ? $user : null;
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function id()
    {
        $user = self::user();

        return $user !== null ? (int) $user['id'] : null;
    }

    // Sends guests to the login page instead of letting them into a protected action
    public static function requireAuth()
    {
        if (!self::check()) {
            Session::flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }

    // Stops a logged-in user from seeing the login/register pages again
    public static function requireGuest()
    {
        if (self::check()) {
            redirect('/photos');
        }
    }

    public static function login($user)
    {
        Session::regenerate();
        Session::set('auth_user', array(
            'id' => (int) $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
        ));
    }

    public static function logout()
    {
        Session::destroy();
    }
}
