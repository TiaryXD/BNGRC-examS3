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

    
}
