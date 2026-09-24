<?php
/**
 * Gallery, photo upload, photo detail page, and delete (owner only)
 */

class PhotoController extends Controller
{
    public function index()
    {
        $style = Request::query('view', 'grid-3');
        $allowedStyles = array('grid-3', 'grid-4', 'list', 'featured');

        if (!in_array($style, $allowedStyles, true)) {
            $style = 'grid-3';
        }

        $this->render('photos/index', array(
            'title' => 'Community gallery',
            'photos' => (new Photo())->latest(50),
            'style' => $style,
        ));
    }

    public function show($id)
    {
        $photoId = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($photoId === false) {
            Response::notFound();
        }

        $photo = (new Photo())->findWithAuthor($photoId);

        if ($photo === null) {
            Response::notFound('This photo does not exist.');
        }

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
            'errors' => array(),
        ));
    }

    public function create()
    {
        Auth::requireAuth();

        $this->render('photos/create', array(
            'title' => 'Share a memory',
            'errors' => array(),
            'taggableUsers' => (new User())->allExcept(Auth::id()),
        ));
    }

    public function store()
    {
        Auth::requireAuth();
        verifyCsrf();

        $input = array(
            'title' => trim(Request::input('title', '')),
            'description' => trim(Request::input('description', '')),
        );
        $errors = Validator::photo($input);
        $upload = isset($_FILES['photo']) ? $_FILES['photo'] : null;

        if (!is_array($upload) || $upload['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['photo'] = 'Choose an image to upload.';
        } elseif ($upload['error'] !== UPLOAD_ERR_OK) {
            $errors['photo'] = 'The image upload could not be completed.';
        } elseif ($upload['size'] > MAX_UPLOAD_BYTES) {
            $errors['photo'] = 'Images must be 8 MB or smaller.';
        } elseif ($this->detectImageType($upload) === null) {
            $errors['photo'] = 'Upload a valid JPEG, PNG, GIF, or WebP image.';
        }

        if (!empty($errors)) {
            $this->render('photos/create', array(
                'title' => 'Share a memory',
                'errors' => $errors,
                'taggableUsers' => (new User())->allExcept(Auth::id()),
            ));
            return;
        }

        // Give the file a random name so uploads can't overwrite each other
        // or be guessed, and pick the extension from the real image type.
        $mimeType = $this->detectImageType($upload);
        $extensionsByMime = array(
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        );
        $extension = isset($extensionsByMime[$mimeType]) ? $extensionsByMime[$mimeType] : 'jpg';
        $fileName = 'photo_' . bin2hex(random_bytes(12)) . '.' . $extension;

        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }

        $destination = UPLOAD_PATH . '/' . $fileName;

        if (!move_uploaded_file($upload['tmp_name'], $destination)) {
            throw new Exception('The uploaded image could not be stored.');
        }

        try {
            $photoId = (new Photo())->create(array(
                'user_id' => Auth::id(),
                'file_name' => $fileName,
                'title' => $input['title'],
                'description' => $input['description'],
            ));
        } catch (Exception $exception) {
            @unlink($destination);
            throw $exception;
        }

        // tag other registered users selected during upload
        $taggedUserIds = Request::input('tags', array());
        if (is_array($taggedUserIds) && !empty($taggedUserIds)) {
            (new Tag())->tagUsers($photoId, $taggedUserIds);
        }

        Session::flash('success', 'Your photo has been added to the gallery.');
        redirect('/photo/' . $photoId);
    }

    // Lets the photo owner tag more registered users after the upload
    public function addTags($id)
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

        if ((int) $photo['user_id'] !== Auth::id()) {
            Response::forbidden('Only the photo owner can tag people in this photo.');
        }

        $taggedUserIds = Request::input('tags', array());
        if (is_array($taggedUserIds) && !empty($taggedUserIds)) {
            (new Tag())->tagUsers($photoId, $taggedUserIds);
            Session::flash('success', 'Tags were added to the photo.');
        }

        redirect('/photo/' . $photoId . '#tags');
    }

    public function delete($id)
    {
        Auth::requireAuth();
        verifyCsrf();

        $photoId = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));

        if ($photoId === false) {
            Response::notFound();
        }

        $photoModel = new Photo();
        $photo = $photoModel->findWithAuthor($photoId);

        if ($photo === null) {
            Response::notFound('This photo does not exist.');
        }

        // Ownership check: a user can only ever delete their own photos
        if ((int) $photo['user_id'] !== Auth::id()) {
            Response::forbidden('Only the photo owner can delete this photo.');
        }

        $deleted = $photoModel->deleteOwned($photoId, Auth::id());

        if ($deleted) {
            $filePath = UPLOAD_PATH . '/' . basename($photo['file_name']);
            if (is_file($filePath)) {
                @unlink($filePath);
            }
            Session::flash('success', 'The photo and its comments were deleted.');
        }

        redirect('/photos');
    }

    // Checks the real file content (not just the extension) so a renamed
    // .exe can't be uploaded pretending to be a photo.
    private function detectImageType($upload)
    {
        $tmpFile = isset($upload['tmp_name']) ? $upload['tmp_name'] : '';
        if ($tmpFile === '' || !is_uploaded_file($tmpFile)) {
            return null;
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo ? finfo_file($fileInfo, $tmpFile) : false;
        if ($fileInfo) {
            finfo_close($fileInfo);
        }

        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');

        if (in_array($mimeType, $allowedTypes, true) && @getimagesize($tmpFile) !== false) {
            return $mimeType;
        }

        return null;
    }
}
