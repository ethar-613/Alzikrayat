<?php
/**
 * Public profile page, shows one user's uploaded photos and the photos
 * they have been tagged in. Anyone can view a profile, logged in or not.
 */

class UserController extends Controller
{
    // this to display user profile 
    // pass user id to it and will validate it to be number >= 1
    // handle errors will display not found page
    // if user found then render users/show and pass info that profile page need it to display
    public function show($id)
    {
        $userId = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($userId === false) {
            Response::notFound();
        }

        $profileUser = (new User())->findById($userId);

        if ($profileUser === null) {
            Response::notFound('This user does not exist.');
        }

        $this->render('users/show', array(
            'title' => $profileUser['first_name'] . ' ' . $profileUser['last_name'],
            'profileUser' => $profileUser,
            'ownPhotos' => (new Photo())->byUser($userId),
            'taggedPhotos' => (new Tag())->photosForUser($userId),
        ));
    }
}
