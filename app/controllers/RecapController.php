<?php

namespace app\controllers;

use app\repositories\RecapRepository;
use Flight;

class RecapController
{
    public static function show($app)
    {
        $repo = new RecapRepository($app->db());
        $data = $repo->getRecap();

        $app->render('dashboard/layout', [
            'page'  => 'recap',
            'title' => 'Récapitulatif',
            'recap' => $data
        ]);
    }

    public static function api($app)
    {
        header('Content-Type: application/json');
        $repo = new RecapRepository($app->db());
        Flight::json([
            'ok' => true,
            'data' => $repo->getRecap()
        ]);
    }

    public static function resetData($app)
    {
        header('Content-Type: application/json');

        try {
            $repo = new \app\repositories\RecapRepository($app->db());
            $repo->resetKeepBaseOnly();
            Flight::json(['ok' => true]);
        } catch (\Throwable $e) {
            Flight::json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

}
