<?php
/**
 * All application routes, registered by hand (no framework routing).
 * connect every HTTP method + URL path with the controller and function that responsible for it 
 */


//create a router object. will save all the routes and then match them at runtime
$router = new Router();

// adding routes to the router object, also its added in specific order

$router->add('GET', '/', array('HomeController', 'index'));
$router->add('GET', '/about', array('HomeController', 'about'));

$router->add('GET', '/login', array('AuthController', 'showLogin'));
$router->add('POST', '/login', array('AuthController', 'login'));
$router->add('GET', '/register', array('AuthController', 'showRegister'));
$router->add('POST', '/register', array('AuthController', 'register'));
$router->add('POST', '/logout', array('AuthController', 'logout'));

$router->add('GET', '/user/{id}', array('UserController', 'show'));

$router->add('GET', '/photos', array('PhotoController', 'index'));
$router->add('GET', '/photo/create', array('PhotoController', 'create'));
$router->add('POST', '/photo/store', array('PhotoController', 'store'));
$router->add('GET', '/photo/{id}', array('PhotoController', 'show'));
$router->add('POST', '/photo/{id}/delete', array('PhotoController', 'delete'));
$router->add('POST', '/photo/{id}/tags', array('PhotoController', 'addTags'));
$router->add('POST', '/photo/{id}/comments', array('CommentController', 'store'));
