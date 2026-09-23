<!-- Handles registration, login, logout and the "last login" cookie -->


<?php 

class AuthController extends Controller
{
    public function showLogin()
    {
        Auth::requireGuest();

        $this->render('auth/login', array(
            'title' => 'Welcome back',
            'errors' => array(),
            'lastLogin' => isset($_COOKIE['last_login']) ? $_COOKIE['last_login'] : null,
        ));
    }

    public function showRegister()
    {
        Auth::requireGuest();

        $this->render('auth/register', array(
            'title' => 'Create your account',
            'errors' => array(),
        ));
    }

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

    public function logout()
    {
        verifyCsrf();
        Auth::logout();
        Session::start();
        Session::flash('success', 'You have been logged out safely.');
        redirect('/');
    }
}
