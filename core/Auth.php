<?php
/**
 * Keeps track of who is logged in, using the session.
 * Only a few non-sensitive fields (id, name, email) are kept in the
 * session -- the password hash is never stored here.
 */

// this layer topped the session. specialized for who is the user who logged in right now 
// it specify the user data that stored in the session and provide guards functions to control the accessibility
class Auth
{
    // Returns array of user's data that stored in the session or null if no one logged in  
    public static function user()
    {
        $user = Session::get('auth_user');

        return is_array($user) ? $user : null;
    }

    // this check if there user logged in or not , it return true or false
    // used in the situation to display Hi username or please loin
    public static function check()
    {
        return self::user() !== null;
    }

    //  return the id of the current user who logged in and turn it to integer also 
    public static function id()
    {
        $user = self::user();

        return $user !== null ? (int) $user['id'] : null;
    }

    // Sends guests to the login page instead of letting them into a protected action
    // its included in the beggining of every controller that need the user to be logged in not just guest - like share photo and delete and so on
    // if they not allowed then will redirect them to login page and show error message
    public static function requireAuth()
    {
        if (!self::check()) {
            Session::flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }

    // Stops a logged-in user from seeing the login/register pages again
    // this for logged in user if tried to enter login/register page it will redirect him to the gallery page 
    public static function requireGuest()
    {
        if (self::check()) {
            redirect('/photos');
        }
    }

    // this when user login it will create new id for the session and set thier non-sesitive data 
    // password will never store. 
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

    // This is when the user logout it let Session destory function to destroy the session data 
    public static function logout()
    {
        Session::destroy();
    }
}
