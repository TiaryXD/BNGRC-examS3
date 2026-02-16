<?php

namespace app\controllers;

use app\repositories\DonRepository;
use Flight;

class StatDonController
{
    public static function getstatdon($app)
    {
        $repo = new DonRepository($app->db());

        $totauxDonsParGenre = $repo->getTotalDonsParGenre();
        $totauxDistribParGenre = $repo->getTotalDistributionsParGenre();

        $distribIndex = [];
        foreach ($totauxDistribParGenre as $row) {
            $key = ($row['don_nom'] ?? '') . '|' . ($row['unite'] ?? '');
            $distribIndex[$key] = (float)($row['total_distributions'] ?? 0);
        }

        $resteParGenre = [];
        foreach ($totauxDonsParGenre as $row) {
            $key = ($row['don_nom'] ?? '') . '|' . ($row['unite'] ?? '');
            $recus = (float)($row['total_dons'] ?? 0);
            $distrib = (float)($distribIndex[$key] ?? 0);

            $resteParGenre[$key] = $recus - $distrib;
        }

        $app->render('dashboard/layout', [
            'page'  => 'stat-don',
            'title' => 'Statistiques des dons',

            'totaux_dons_par_type' => $totauxDonsParGenre,       // (tu peux renommer en _par_genre si tu veux)
            'totaux_distrib_par_type' => $totauxDistribParGenre, // idem
            'reste_par_type' => $resteParGenre,                 // reste par "don_nom|unite"
        ]);
    }

}
