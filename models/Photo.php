<!--  Photo model, handles all SQL for the photos table. -->

<?php


class Photo extends Model
{
    // Newest photos first, joined with the uploader's name for the gallery
    public function latest($limit = 12)
    {
        $limit = max(1, min($limit, 50));

        $statement = $this->database->query(
            'SELECT photos.id, photos.user_id, photos.file_name, photos.title,
                    photos.description, photos.date_time,
                    users.first_name, users.last_name
             FROM photos
             INNER JOIN users ON users.id = photos.user_id
             ORDER BY photos.date_time DESC
             LIMIT ' . $limit
        );

        return $statement->fetchAll();
    }

    public function findWithAuthor($id)
    {
        $statement = $this->database->prepare(
            'SELECT photos.id, photos.user_id, photos.file_name, photos.title,
                    photos.description, photos.date_time,
                    users.first_name, users.last_name
             FROM photos
             INNER JOIN users ON users.id = photos.user_id
             WHERE photos.id = :id
             LIMIT 1'
        );
        $statement->execute(array('id' => $id));
        $photo = $statement->fetch();

        return $photo === false ? null : $photo;
    }

    // Saves the photo's metadata after the file itself has already been uploaded to disk
    public function create($input)
    {
        $statement = $this->database->prepare(
            'INSERT INTO photos (user_id, file_name, title, description)
             VALUES (:user_id, :file_name, :title, :description)'
        );
        $statement->execute(array(
            'user_id' => (int) $input['user_id'],
            'file_name' => $input['file_name'],
            'title' => trim($input['title']),
            'description' => isset($input['description']) && trim($input['description']) !== '' ? trim($input['description']) : null,
        ));

        return (int) $this->database->lastInsertId();
    }

    // Only deletes the row if it really belongs to this user
    public function deleteOwned($photoId, $userId)
    {
        $statement = $this->database->prepare('DELETE FROM photos WHERE id = :id AND user_id = :user_id');
        $statement->execute(array('id' => $photoId, 'user_id' => $userId));

        return $statement->rowCount() === 1;
    }

    public function count()
    {
        return (int) $this->database->query('SELECT COUNT(*) FROM photos')->fetchColumn();
    }

    public function countByUser($userId)
    {
        $statement = $this->database->prepare('SELECT COUNT(*) FROM photos WHERE user_id = :user_id');
        $statement->execute(array('user_id' => $userId));

        return (int) $statement->fetchColumn();
    }
}
