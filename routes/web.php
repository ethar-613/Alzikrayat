<!-- All application routes, registered by hand, mine -->

<?php

$router = new Router();

$router->add('GET', '/', array('HomeController', 'index'));
$router->add('GET', '/about', array('HomeController', 'about'));

$router->add('GET', '/login', array('AuthController', 'showLogin'));
$router->add('POST', '/login', array('AuthController', 'login'));
$router->add('GET', '/register', array('AuthController', 'showRegister'));
$router->add('POST', '/register', array('AuthController', 'register'));
$router->add('POST', '/logout', array('AuthController', 'logout'));

$router->add('GET', '/photos', array('PhotoController', 'index'));
$router->add('GET', '/photo/create', array('PhotoController', 'create'));
$router->add('POST', '/photo/store', array('PhotoController', 'store'));
$router->add('GET', '/photo/{id}', array('PhotoController', 'show'));
$router->add('POST', '/photo/{id}/delete', array('PhotoController', 'delete'));
$router->add('POST', '/photo/{id}/comments', array('CommentController', 'store'));
