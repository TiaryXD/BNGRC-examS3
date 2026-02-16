<?php

namespace app\controllers;

use app\repositories\DonRepository;
use Flight;

class DonController
{
    public static function historique($app)
    {
        $repo = new DonRepository($app->db());
        $dons = $repo->get_historique();

        $app->render('dashboard/layout', [
            'dons'  => $dons,
            'page' => 'dons',
            'title' => 'Historique des dons'
        ]);
    }
}
