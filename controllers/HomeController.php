<?php
/**
 * Landing page and About Us page.
 */

class HomeController extends Controller
{
    // this handle the home page display
    // it make objects from user , photo and comment models 
    // to display statistics about the website users count , photo and comment all count at the home page
    // it will pass this values it get to the home/index page to display it there
    public function index()
    {
        $photoModel = new Photo();
        $userModel = new User();
        $commentModel = new Comment();

        $this->render('home/index', array(
            'title' => 'A living album for the moments that matter',
            'latestPhotos' => $photoModel->latest(9),
            'photoCount' => $photoModel->count(),
            'userCount' => $userModel->count(),
            'commentCount' => $commentModel->count(),
        ));
    }

    // handle about us page display 
    // it just render about us page and pass the title for the page
    public function about()
    {
        $this->render('home/about', array(
            'title' => 'About Alzikrayat',
        ));
    }
}
