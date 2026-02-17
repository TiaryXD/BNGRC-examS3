<?php

use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

use app\controllers\AuthController;
use app\controllers\HomeController;
use app\controllers\BesoinController;
use app\controllers\DonController;
use app\controllers\StatDonController;
use app\controllers\AchatController;

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

    $router->get('/404', function(){
        Flight::render('error/404');
    });

    $router->get('/ville', function() use ($app) {
        BesoinController::showVille($app);
    });

    $router->get('/ville/@id', function($id) use ($app) {
        BesoinController::showVilleById($app, $id);
    });

    $router->get('/dons', function() use ($app) {
        DonController::historique($app);
    });
    
    $router->get('/ajout-besoin/@id', function() use ($app) {
        BesoinController::showCreate($app);
    });
    $router->post('/save-besoin', function() use ($app) {
        BesoinController::saveBesoinbyidville($app);
    });

    $router->get('/ajout-don', function() use ($app) {
        DonController::createdon($app);
    });
    $router->post('/save-don', function() use ($app) {
        DonController::savedon($app);
    });
    
    $router->get('/stat-don', function() use ($app) {
        StatDonController::getstatdon($app);
    });

    $router->get('/distribuer', function() use ($app) {
        DonController::showDistribuerForm($app);
    });

    $router->post('/distribuer/save', function() use ($app) {
        DonController::saveDistribution($app);
    });
    $router->get('/achat', function() use ($app) { 
        AchatController::getlisteachat($app);
    }); 
    $router->get('/achat/form-achat', function() use ($app) { 
        AchatController::formAchat($app); 
    }); 
    $router->post('/achat/ajout-achat', function() use ($app) { 
        AchatController::saveachat($app); 
    });
}, [ SecurityHeadersMiddleware::class ]);