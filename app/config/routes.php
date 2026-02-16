<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

use app\controllers\AuthController;
use app\controllers\HomeController;

/** 
 * @var Router $router 
 * @var Engine $app
 */

$router->group('', function(Router $router) use ($app) {

    $router->get('/login', function() use ($app) {
        AuthController::showLogin($app);
    });

    $router->post('/login', function() use ($app) {
       AuthController::postLogin($app);
    });

    $router->post('/api/validate/auth', function() use ($app) {
        AuthController::validateLoginAjax($app);
    });

    $router->get('/logout', function(){
        AuthController::logout();
    });

    $router->get('/', function() use ($app) {
        HomeController::showHome($app);
    });

    $router->get('/recherche', function() use ($app) {
        HomeController::showSearch($app);
    });

    $router->get('/inventaire', function() use ($app) {
        HomeController::showInventaire($app);
    });

    $router->get('/404', function(){
        Flight::render('error/404');
    });

    Flight::map('notFound', function(){
        Flight::redirect('/404?error=PageNotFound');
    });
	
}, [ SecurityHeadersMiddleware::class ]);