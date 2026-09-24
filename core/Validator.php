<?php
/**
 * Server-side validation.
 * The forms also use HTML5 (required, pattern, maxlength) and a bit of
 * JavaScript for instant feedback, but those can be bypassed, so every
 * rule is checked again here before anything touches the database.
 */

class Validator
{
    public static function registration($input)
    {
        $errors = array();

        $firstName = isset($input['first_name']) ? trim($input['first_name']) : '';
        $lastName = isset($input['last_name']) ? trim($input['last_name']) : '';
        $email = isset($input['email']) ? trim($input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

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
    private static function checkOptionalLength($input, $field, $maxLength, &$errors)
    {
        $value = isset($input[$field]) ? trim($input[$field]) : '';

        if ($value !== '' && strlen($value) > $maxLength) {
            $errors[$field] = ucfirst($field) . ' must be under ' . $maxLength . ' characters.';
        }
    }
}
