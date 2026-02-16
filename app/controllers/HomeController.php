<?php

namespace app\controllers;

use Flight;

class HomeController
{
    public static function showHome($app)
    {
        if (!isset($_SESSION['user'])) {
            $user = '';
        } else {
            $user = $_SESSION['user'];
        }

        $app->render('home/layout', [
            'page' => 'home',
            'title' => 'Accueil',
            'user' => $user,
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
