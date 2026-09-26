<?php
/**
 * Base Controller class.
 * Handles rendering a view file inside the common page layout, and stores
 * a few small helper method used by all the controllers
 */


// this is the abstract base class that all thd controllers inheret from it 
// its primary responsibility to provide unfied render() func to display aby view within the main  layout of the app
// and passing shared data to each page automaticaly without dublicate 
// its function protected to call it only from sub controllers
abstract class Controller
{
    // Loads a view file, then wraps it in the shared layout (header/footer)
    // Argument: $view is the path to the page , $data array contaion data needed for the page display and will use in the html code to display it
    // first it build the view path from its name $view and make it clean
    // then check if the file is exists or not. if not it will throw exception. 
    // then turn data array key to normal var
    // then get the user logged in or null if not logged. and make him available for every page  automatically.
    // then make output buffering that store every eho or html from now on , it wil store the viewFile 
    // then take the contect stored in the buffer to save it as string in $content. and stop the buffering and delete it 
    // it will check the page title , if no title then show its title  to be the application name
    // then store success message and error message temorary in the session. and display after redirect once and then deleted automatically
    // then contain the main layout that will display the $content in its suite place make use of its data variables
    // and cleaning any old data input stoed temporary 
    // so it will show contect with the msin layout an dwith user data if they logged or not so hes guest
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

}
