<?php
/**
 * Comment model, handles all SQL for the comments table
 */

class Comment extends Model
{
    // All comments for one photo, oldest first, with the commenter's name
    // pass photo id that want to get its comment 
    // return array of the comments data from the table. and user name that write this comment 
    public function forPhoto($photoId)
    {
        $statement = $this->database->prepare(
            'SELECT comments.id, comments.photo_id, comments.user_id, comments.comment,
                    comments.date_time, users.first_name, users.last_name
             FROM comments
             INNER JOIN users ON users.id = comments.user_id
             WHERE comments.photo_id = :photo_id
             ORDER BY comments.date_time ASC, comments.id ASC'
        );
        $statement->execute(array('photo_id' => $photoId));

        return $statement->fetchAll();
    }

    // create comment data to save on comment table
    // pass input data array that have the comment itself , photo id and user id.
    // return the id of this comment to display it immediatly on the page
    public function create($input)
    {
        $statement = $this->database->prepare(
            'INSERT INTO comments (photo_id, user_id, comment)
             VALUES (:photo_id, :user_id, :comment)'
        );
        $statement->execute(array(
            'photo_id' => (int) $input['photo_id'],
            'user_id' => (int) $input['user_id'],
            'comment' => trim($input['comment']),
        ));

        return (int) $this->database->lastInsertId();
    }

    // return the number of the comments on the website to display it with statistics on the home page
    public function count()
    {
        return (int) $this->database->query('SELECT COUNT(*) FROM comments')->fetchColumn();
    }
}
