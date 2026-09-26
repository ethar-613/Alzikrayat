<?php
/**
 * Photo model, handles all SQL for the photos table.
 */


// class to handle all sql related to photos table with permanent JOIN on the users table to retrive photo owner name
class Photo extends Model
{
    // Newest photos first, joined with the uploader's name for the gallery
    // passing argument the limit needed to display as the latest photos. but the default value for limit is 12 
    // it will prepare the query and bind it with the limit that 
    // then execute it and Return photos info
    public function latest($limit = 12)
    {
        $limit = max(1, min($limit, 50));

        $statement = $this->database->prepare(
            'SELECT photos.id, photos.user_id, photos.file_name, photos.title,
                    photos.description, photos.date_time,
                    users.first_name, users.last_name
             FROM photos
             INNER JOIN users ON users.id = photos.user_id
             ORDER BY photos.date_time DESC
             LIMIT :limit' 
        );
        
        $statement->bindValue('limit', $limit , PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    // this to use it on the Photo Details View page
    // pass photo id to the function to return the photo metadata and user name
    // Return null or photo data array
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
    // when upload photo successfully this add the meta data to photo table
    // pass the metadata array, Return the id of it to show its details immediaty
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
    // pass the photo id wanted to delete and the user id who tried to delete it 
    // if its match this user own this photo then delete 
    // Return true if the photo owned by this user. if not then return false
    public function deleteOwned($photoId, $userId)
    {
        $statement = $this->database->prepare('DELETE FROM photos WHERE id = :id AND user_id = :user_id');
        $statement->execute(array('id' => $photoId, 'user_id' => $userId));

        return $statement->rowCount() === 1;
    }

    // this used by homeController to count the photos on website then show the statistics on home page
    // return the number of the photos from the table
    public function count()
    {
        return (int) $this->database->query('SELECT COUNT(*) FROM photos')->fetchColumn();
    }

    // this statistic that display on the profile for the user that count how many photos this user uploaded 
    // pass user id to it and count the photos related to them. 
    // return the number of photos related to the user
    public function countByUser($userId)
    {
        $statement = $this->database->prepare('SELECT COUNT(*) FROM photos WHERE user_id = :user_id');
        $statement->execute(array('user_id' => $userId));

        return (int) $statement->fetchColumn();
    }

    // All photos uploaded by one user, newest first - used on the profile page
    // to fetch all photos uploaded by specific user. 
    // pass user id to it.
    // return array of all photos related to this user metadata and user name 
    public function byUser($userId)
    {
        $statement = $this->database->prepare(
            'SELECT photos.id, photos.user_id, photos.file_name, photos.title,
                    photos.description, photos.date_time,
                    users.first_name, users.last_name
             FROM photos
             INNER JOIN users ON users.id = photos.user_id
             WHERE photos.user_id = :user_id
             ORDER BY photos.date_time DESC'
        );
        $statement->execute(array('user_id' => $userId));

        return $statement->fetchAll();
    }
}
