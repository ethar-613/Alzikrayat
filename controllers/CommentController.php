<!-- Adds a comment to a photo. Only logged-in users can comment -->

<?php

class CommentController extends Controller
{
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
            $this->render('photos/show', array(
                'title' => $photo['title'],
                'photo' => $photo,
                'comments' => (new Comment())->forPhoto($photoId),
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
