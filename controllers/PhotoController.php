<?php
/**
 * Gallery, photo upload, photo detail page, and delete (owner only).
 */

// to handle photo details -upload-delete-tag-gallery view
class PhotoController extends Controller
{
    //this handle the gallery display
    // first it get the choosed style from the request quey string, and compare it with allowed styles 
    // if it not in allowed styles then just set it as default grid-3
    // then render page to display the gallery with the 9 latest photos and the choosed style
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

    // used to show specific photo by its id 
    // first check if the id sent is actually a number and its greater than or equal to 1 
    // if not match the check then show page not found error
    // if its validate then search for the photo by its id with the auther name.
    // check if the user logged in to store all taggable users in array to display it later so this user how own this photo can tag anyone
    // then redirect the user to photo show page 
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

    // used to create new photo.
    // it require the user to be logged in to do it then redirect him to the create photo page
    public function create()
    {
        Auth::requireAuth();

        $this->render('photos/create', array(
            'title' => 'Share a memory',
            'errors' => array(),
            'taggableUsers' => (new User())->allExcept(Auth::id()),
        ));
    }

    // using to store the photo metadata 
    // check first if the user logged in and check csrf tokens
    // get the title and descriotion. get the image file and check for its errors 
    // if any error found then rerender the page
    // then make random name with extention. and male it start with photo_
    // then store the actual photo at the upload path 
    // then try to insert the meta data in the database. and if it fails then delete the file from the uploads folder and throw exception
    // if success then can tag other users during upload
    // then display message and redirect user to the new photo details page
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

        if (!is_array($upload) || $upload['error'] === UPLOAD_ERR_NO_FILE) { //check if the file did sent
            $errors['photo'] = 'Choose an image to upload.';
        } elseif ($upload['error'] !== UPLOAD_ERR_OK) {                      //check if the file uploaded correctly technically
            $errors['photo'] = 'The image upload could not be completed.';
        } elseif ($upload['size'] > MAX_UPLOAD_BYTES) {                      //check if file size smaller than 8MB 
            $errors['photo'] = 'Images must be 8 MB or smaller.';
        } elseif ($this->detectImageType($upload) === null) {                //check if the file is actually an image
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

        if (!move_uploaded_file($upload['tmp_name'], $destination)) { //to ensure that the image came from good http
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
    // pass photo id that want to add tag to
    // it check tag checkboxes checked from authoried user then add it to the database an display it and success message
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


    // Delete a photo only if user own it
    // check the id. search for the photo at database. check if they own it.
    // if yes then delete it from database and from the physical path. and show success message 
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
        if ($tmpFile === '' || !is_uploaded_file($tmpFile)) { // is_uploaded_file usedto check if its came from real http pat
            return null;
        }

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE); // here reads the actual file magic bytes to know its real extention 
        $mimeType = $fileInfo ? finfo_file($fileInfo, $tmpFile) : false;
        if ($fileInfo) {
            finfo_close($fileInfo);
        }

        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');

        // @getimagesize used as additional security layer to check the file type
        if (in_array($mimeType, $allowedTypes, true) && @getimagesize($tmpFile) !== false) {
            return $mimeType;
        }

        return null;
    }
}
