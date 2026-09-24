<?php
/**
 * The Base Controller class
 * Handles rendering a view file inside the common page layout, and stores a few small helper methods used by all the controllers
 */

abstract class Controller
{
    // Loads a view file, then wraps it in the shared layout (header/footer)
    protected function render($view, $data = array())
    {
        $viewFile = APP_ROOT . '/views/' . trim($view, '/') . '.php';

        if (!is_file($viewFile)) {
            throw new Exception('View file not found: ' . $view);
        }

        // Turn each array key into a normal PHP variable for the view file
        extract($data);
        $currentUser = Auth::user();

        // Capture the view's HTML output so we can insert it into the layout
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if (!isset($title)) {
            $title = APP_NAME;
        }
        $successMessage = Session::consumeFlash('success');
        $errorMessage = Session::consumeFlash('error');

        require APP_ROOT . '/views/layout/main.php';
        Session::remove('old_input');
    }

    protected function redirectTo($path)
    {
        redirect($path);
    }
}
