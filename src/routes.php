<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/signin', 'LoginController@signin');
$router->post('/signin', 'LoginController@signinAction');

$router->get('/signup', 'LoginController@signup');
$router->post('/signup', 'LoginController@signupAction');

$router->post('/post/new', 'PostController@new');

$router->get('/perfil/{id}/follow', 'ProfileController@follow');
$router->get('/perfil/{id}', 'ProfileController@index');
$router->get('/perfil', 'ProfileController@index');

$router->get('/sair', 'LoginController@logout');

//$router->get('pesquisa');
//$router->get('amigos');
//$router->get('fotos');
//$router->get('config');