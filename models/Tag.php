<?php
/**
 * Tag model, lets a user tag other registered users
 */

class Tag extends Model
{
    // Every user tagged in one photo, with their name, newest first
    public function forPhoto($photoId)
    {
        $statement = $this->database->prepare(
            'SELECT users.id, users.first_name, users.last_name
             FROM photo_tags
             INNER JOIN users ON users.id = photo_tags.user_id
             WHERE photo_tags.photo_id = :photo_id
             ORDER BY photo_tags.date_time ASC'
        );
        $statement->execute(array('photo_id' => $photoId));

        return $statement->fetchAll();
    }

    // Tags a list of user IDs in one photo. Already-tagged users and
    // unknown IDs are simply skipped instead of raising an error.
    public function tagUsers($photoId, $userIds)
    {
        $statement = $this->database->prepare(
            'INSERT IGNORE INTO photo_tags (photo_id, user_id) VALUES (:photo_id, :user_id)'
        );

        foreach ($userIds as $userId) {
            $userId = (int) $userId;
            if ($userId > 0) {
                $statement->execute(array('photo_id' => $photoId, 'user_id' => $userId));
            }
        }
    }

    // Every photo one user has been tagged in, newest first - used on the profile page
    public function photosForUser($userId)
    {
        $statement = $this->database->prepare(
            'SELECT photos.id, photos.user_id, photos.file_name, photos.title,
                    photos.description, photos.date_time,
                    users.first_name, users.last_name
             FROM photo_tags
             INNER JOIN photos ON photos.id = photo_tags.photo_id
             INNER JOIN users ON users.id = photos.user_id
             WHERE photo_tags.user_id = :user_id
             ORDER BY photos.date_time DESC'
        );
        $statement->execute(array('user_id' => $userId));

        return $statement->fetchAll();
    }
}
