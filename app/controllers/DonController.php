<?php

namespace app\controllers;

use app\repositories\DonRepository;
use Flight;

class DonController
{
    public static function historique()
    {
        $repo = new DonRepository(Flight::get('db'));

        $dons = $repo->get_historique();

        Flight::render('dons/layout', [
            'dons'  => $dons,
            'page' => 'dons',
            'title' => 'Historique des dons'
        ]);
    }
}
