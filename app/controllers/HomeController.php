<?php

namespace app\controllers;

use Flight;

class HomeController
{
    public static function showHome($app)
    {
        if (!isset($_SESSION['user'])) {
            Flight::redirect('/login');
            return;
        }

        $app->render('home/layout', [
            'page' => 'home',
            'title' => 'Accueil',
            'user' => $_SESSION['user'],
        ]);
    }

    public static function showSearch($app)
    {
        $app->render('home/layout', [
            'page' => 'rechercher',
            'title' => 'Recherche',
        ]);
    }

    public static function showInventaire($app)
    {
        $app->render('home/layout', [
            'page' => 'inventaire',
            'title' => 'Inventaire',
        ]);
    }
}
