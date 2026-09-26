<?php
/**
 * User model, handles all SQL for the users table.
 */

// class to handle all database operations that related to the users table:
// search for user to log in, search for a public profile, check email uniqueness ,create new account, app statistics
class User extends Model
{
    // Used when logging in
    // email is the only unique value so will find the user by email
    // pass the email that the user tried to log in with
    // normalize the email to be lower case and remove spaces, and pass it to execute the sql query to match
    // if it find the user will return user data array
    // if not found then return null
    public function findByEmail($email)
    {
        $statement = $this->database->prepare(
            'SELECT id, first_name, last_name, email, password, location, description, occupation
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $statement->execute(array('email' => strtolower(trim($email))));
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    // Used to show a public profile (no password included)
    // دي منفصلة عن ايجاد المستخدم ببريده الالكتروني لانه ذي لعرض بياناته فعشان ما يكون في اي فرصة لكلمة المرور تتسرب عملت دي للعرض
    // بتعمل نفس الدالة السابقة لكن ما بترجع كلمة المرور فقط
    // argument: an id to search, Return:user data array(without password) or null if not found
    public function findById($id)
    {
        $statement = $this->database->prepare(
            'SELECT id, first_name, last_name, email, location, description, occupation
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(array('id' => $id));
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    // this check the uniqueness of the email. cuz the email must be UNIQUE
    // pass the email to it.it willnormalize it and execute sql query to check how many times this email exist in the table
    // return 0 or 1 so it exists or not
    public function emailExists($email)
    {
        $statement = $this->database->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $statement->execute(array('email' => strtolower(trim($email))));

        return (int) $statement->fetchColumn() > 0;
    }

    // Creates a new account. Password is hashed here, never stored as plain text.
    // pass the data input array to it.
    // it prepare the insertion query and then execute it with insert the input data on it.
    // it hash the password before stroe it using password_hash() method with PASSWORD_DEFAULT
    // PASSWORD_DEFAULT uses Bcrypt algorithm for now and maybe change to stronger algorithm in future :> 
    // it Return auto incremint id to the row just added. controller use it to let the user log in immediatly after register
    public function create($input)
    {
        $statement = $this->database->prepare(
            'INSERT INTO users
                (first_name, last_name, email, password, location, description, occupation)
             VALUES
                (:first_name, :last_name, :email, :password, :location, :description, :occupation)'
        );
        $statement->execute(array(
            'first_name' => trim($input['first_name']),
            'last_name' => trim($input['last_name']),
            'email' => strtolower(trim($input['email'])),
            'password' => password_hash($input['password'], PASSWORD_DEFAULT), 
            'location' => isset($input['location']) && trim($input['location']) !== '' ? trim($input['location']) : null,
            'description' => isset($input['description']) && trim($input['description']) !== '' ? trim($input['description']) : null,
            'occupation' => isset($input['occupation']) && trim($input['occupation']) !== '' ? trim($input['occupation']) : null,
        ));

        return (int) $this->database->lastInsertId();
    }

    // this is using to display the statistics on the home page it count the users at the table and return the number
    public function count()
    {
        return (int) $this->database->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    // Every other registered user, used to build the "tag people" checklist
    // pass the user id to it so it get all users in the app names except for the user have this id
    //  then return all thier names
    public function allExcept($userId)
    {
        $statement = $this->database->prepare(
            'SELECT id, first_name, last_name
             FROM users
             WHERE id != :user_id
             ORDER BY first_name ASC, last_name ASC'
        );
        $statement->execute(array('user_id' => $userId));

        return $statement->fetchAll();
    }
}
