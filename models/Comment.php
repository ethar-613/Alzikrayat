<!-- Comment model, handles all SQL for the comments table -->

<?php

class Comment extends Model
{
    // All comments for one photo, oldest first, with the commenter's name
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

    public function count()
    {
        return (int) $this->database->query('SELECT COUNT(*) FROM comments')->fetchColumn();
    }
}
