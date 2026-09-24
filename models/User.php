<?php
/**
 * User model, handles all SQL for the users table.
 */

class User extends Model
{
    // Used when logging in
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

    public function emailExists($email)
    {
        $statement = $this->database->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $statement->execute(array('email' => strtolower(trim($email))));

        return (int) $statement->fetchColumn() > 0;
    }

    // Creates a new account. Password is hashed here, never stored as plain text.
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

    public function count()
    {
        return (int) $this->database->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    // Every other registered user, used to build the "tag people" checklist
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
