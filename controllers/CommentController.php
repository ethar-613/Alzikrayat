<?php
/**
 * Adds a comment to a photo. Only logged-in users can comment.
 */

class CommentController extends Controller
{
    // add comment to a photo
    // pass photo id that want to put comment for it 
    // check authentication and csrf tokens and the id it its number greater or equal to 1 
    //  get the photo metadata by the id.
    // put the input comment on array $input and check its validaty. if there error then rerender the page
    // if valid then show success message and display the comment immediatly
    public function store($id)
    {
        Auth::requireAuth();
        verifyCsrf();

        $photoId = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
        if ($photoId === false) {
            Response::notFound();
        }

        $photo = (new Photo())->findWithAuthor($photoId);
        if ($photo === null) {
            Response::notFound('This photo does not exist.');
        }

        $input = array('comment' => trim(Request::input('comment', '')));
        $errors = Validator::comment($input);

        if (!empty($errors)) {
            $currentUser = Auth::user();
            $taggableUsers = array();
            if ($currentUser !== null && (int) $photo['user_id'] === (int) $currentUser['id']) {
                $taggableUsers = (new User())->allExcept((int) $currentUser['id']);
            }

            $this->render('photos/show', array(
                'title' => $photo['title'],
                'photo' => $photo,
                'comments' => (new Comment())->forPhoto($photoId),
                'taggedUsers' => (new Tag())->forPhoto($photoId),
                'taggableUsers' => $taggableUsers,
                'errors' => $errors,
            ));
            return;
        }

        (new Comment())->create(array(
            'photo_id' => $photoId,
            'user_id' => Auth::id(),
            'comment' => $input['comment'],
        ));

        Session::flash('success', 'Your comment has been added.');
        redirect('/photo/' . $photoId . '#comments');
    }
}
