<!-- Landing page and About Us page -->

<?php


class HomeController extends Controller
{
    public function index()
    {
        $photoModel = new Photo();
        $userModel = new User();

        $this->render('home/index', array(
            'title' => 'A living album for the moments that matter',
            'latestPhotos' => $photoModel->latest(6),
            'photoCount' => $photoModel->count(),
            'userCount' => $userModel->count(),
        ));
    }

    public function about()
    {
        $this->render('home/about', array(
            'title' => 'About Alzikrayat',
        ));
    }
}
