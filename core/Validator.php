<?php
/**
 * Server-side validation.
 * The forms also use HTML5 (required, pattern, maxlength) and a bit of
 * JavaScript for instant feedback, but those can be bypassed, so every
 * rule is checked again here before anything touches the database.
 */

// This class grouup all Server-side validation rules. as the third layer of validation after HTML5 nad JS validation
// each function accept an input array and Return an error array, if its empty so no errors. also they clear the errors array for every check
// note: for registeration he check for email to be UNIQUE its done on AuthController.php and Models/User.php
class Validator
{
    // this used for registration form required input
    // put input array values in php variables and using trim() for input fields to delete the spaces in the front and back the text
    // then check every input 
    // for first and last name it check if the field empty, or it longer than 50 chars , or does not match the RegEx pattern. 
    // if at lease one condition is true it will be count as error and stor it at errors array
    // about email, check it using filter_var() with FILTER_VALIDATE_EMAIL , this built-in function is better than RegEx by my hand and more reliable so i prefer it
    // for password, need to be more than 8 letters and less than 72. 72 cuz the Bcrypt alg ignore any char byond 72 bytes of a password.
    // then check the optional field against thier char limit if needed
    // Then return the errors array
    public static function registration($input)
    {
        $errors = array();

        $firstName = isset($input['first_name']) ? trim($input['first_name']) : ''; 
        $lastName = isset($input['last_name']) ? trim($input['last_name']) : '';
        $email = isset($input['email']) ? trim($input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

        // RegEx pattern here allow only english letters small and capital , can be multible words seperate by space " " or - 
        if ($firstName === '' || strlen($firstName) > 50 || !preg_match('/^[A-Za-z]+([ -][A-Za-z]+)*$/', $firstName)) {
            $errors['first_name'] = 'Use letters only for a first name up to 50 characters.';
        }

        if ($lastName === '' || strlen($lastName) > 50 || !preg_match('/^[A-Za-z]+([ -][A-Za-z]+)*$/', $lastName)) {
            $errors['last_name'] = 'Use letters only for a last name up to 50 characters.';
        }

        if ($email === '' || strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if (strlen($password) < 8 || strlen($password) > 72) {
            $errors['password'] = 'Password must contain between 8 and 72 characters.';
        }

        self::checkOptionalLength($input, 'location', 100, $errors);
        self::checkOptionalLength($input, 'occupation', 100, $errors);
        self::checkOptionalLength($input, 'description', 2000, $errors);

        return $errors;
    }

    // THis check for the login input form 
    // do verfication more simpler than register cuz every user registered already his data checked and accepted , so no need to repeat all the registerated rules
    // it just check the if its valid email address , and the password to not be empty
    public static function login($input)
    {
        $errors = array();

        $email = isset($input['email']) ? trim($input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Enter your password.';
        }

        return $errors;
    }

    // this check photo input form data 
    // check the title for the photo to not be empty or more than 200 character
    // checking description if there any to not be more than 5000 character
    public static function photo($input)
    {
        $errors = array();

        $title = isset($input['title']) ? trim($input['title']) : '';

        if ($title === '' || strlen($title) > 200) {
            $errors['title'] = 'A photo title is required and must be under 200 characters.';
        }

        self::checkOptionalLength($input, 'description', 5000, $errors);

        return $errors;
    }

    // check the comment input fields
    // check the comment to not be empty  or more than 2000 char
    public static function comment($input)
    {
        $errors = array();
        $comment = isset($input['comment']) ? trim($input['comment']) : '';

        if ($comment === '' || strlen($comment) > 2000) {
            $errors['comment'] = 'Write a comment between 1 and 2,000 characters.';
        }

        return $errors;
    }

    // Small shared helper: fields that are optional but still have a max length
    // used by other functions in this class that have optional field to chech 
    // pass it the input array, field name , max length for this field to check for it, 
    // and passing errors array by refrence so the changes in this function to be change in the original array in the method that called this method
    private static function checkOptionalLength($input, $field, $maxLength, &$errors)
    {
        $value = isset($input[$field]) ? trim($input[$field]) : '';

        if ($value !== '' && strlen($value) > $maxLength) {
            $errors[$field] = ucfirst($field) . ' must be under ' . $maxLength . ' characters.';
        }
    }
}
