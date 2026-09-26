<?php
/**
 * Handles registration, login, logout and the "last login" cookie.
 */

// this class handle all the authentication logic: desplay a form , and all about register-login-logout-last Login cookie
class AuthController extends Controller
{
    // used to show the Login page for the user if they guest but if not then return him to gallery page
    // if its really a guest it will show login page and send the data with it to display in the page
    // read the last_login cookie to display it in login form for user
    // and empty errors array cuz no errors yet its the first go to the page
    public function showLogin()
    {
        Auth::requireGuest();

        $this->render('auth/login', array(
            'title' => 'Welcome back',
            'errors' => array(),
            'lastLogin' => isset($_COOKIE['last_login']) ? $_COOKIE['last_login'] : null,
        ));
    }

    // This is do the same thing as showLogin() but for register page and without last login cookie
    public function showRegister()
    {
        Auth::requireGuest();

        $this->render('auth/register', array(
            'title' => 'Create your account',
            'errors' => array(),
        ));
    }

    // handle the login. 
    // check if they guest first then verify the csrf token. then read email and password sent with the request
    // check if inputes validate and return any error found 
    // check if the user with this email is exists and the password match
    // if all true then log user in and set last_login cookie and redirect him to the gallery
    public function login()
    {
        Auth::requireGuest();
        verifyCsrf();

        $input = array(
            'email' => trim(Request::input('email', '')),
            'password' => Request::input('password', ''),
        );
        $errors = Validator::login($input);

        if (!empty($errors)) {
            Session::set('old_input', array('email' => $input['email']));
            $this->render('auth/login', array(
                'title' => 'Welcome back',
                'errors' => $errors, 
                'lastLogin' => isset($_COOKIE['last_login']) ? $_COOKIE['last_login'] : null,
            ));
            return;
        }

        $user = (new User())->findByEmail($input['email']);

        if ($user === null || !password_verify($input['password'], $user['password'])) {
            Session::set('old_input', array('email' => $input['email']));
            $this->render('auth/login', array(
                'title' => 'Welcome back',
                'errors' => array('general' => 'The email or password is incorrect.'),
                'lastLogin' => isset($_COOKIE['last_login']) ? $_COOKIE['last_login'] : null,
            ));
            return;
        }

        Auth::login($user);

        // Remember when this browser last logged in successfully, for 7 days
        setcookie('last_login', date('Y-m-d H:i:s'), time() + (7 * 24 * 60 * 60), '/');

        Session::remove('old_input');
        Session::flash('success', 'Welcome back, ' . $user['first_name'] . '.');
        redirect('/photos');
    }

    // handle register for new users
    // check if they guest first then verify the csrf token. get all input from user by input() and put it in array
    // validate them and check errors. if no errors and this email alreay exists then add email error to the error array 
    // then check the error array and if there no error then create the user row at the database and show successs message and redirect to login page
    public function register()
    {
        Auth::requireGuest();
        verifyCsrf();

        $input = array(
            'first_name' => trim(Request::input('first_name', '')),
            'last_name' => trim(Request::input('last_name', '')),
            'email' => trim(Request::input('email', '')),
            'password' => Request::input('password', ''),
            'location' => trim(Request::input('location', '')),
            'occupation' => trim(Request::input('occupation', '')),
            'description' => trim(Request::input('description', '')),
        );
        $errors = Validator::registration($input);
        $userModel = new User();

        if (empty($errors) && $userModel->emailExists($input['email'])) {
            $errors['email'] = 'An account with this email already exists.';
        }

        if (!empty($errors)) {
            $oldInput = $input;
            unset($oldInput['password']);
            Session::set('old_input', $oldInput);

            $this->render('auth/register', array(
                'title' => 'Create your account',
                'errors' => $errors,
            ));
            return;
        }

        $userModel->create($input);
        Session::remove('old_input');
        Session::flash('success', 'Your account is ready. Please log in.');
        redirect('/login');
    }

    // handle logout for logged in user
    // check / verify the csrf token. then logout by destroing the session.
    // and start the session to show success message and redirect to the main
    public function logout()
    {
        verifyCsrf();
        Auth::logout();
        Session::start();
        Session::flash('success', 'You have been logged out safely.');
        redirect('/');
    }
}
