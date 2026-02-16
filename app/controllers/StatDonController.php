<?php

namespace app\controllers;

use app\repositories\DonRepository;
use Flight;

class StatDonController
{
    public static function getstatdon($app)
    {
        $repo = new DonRepository;($app->db());

        // Tous types (tableaux)
        $totauxDonsParType = $repo->getTotalDonsParType();
        $totauxDistribParType = $repo->getTotalDistributionsParType();

        // (Optionnel) Calcul du reste par type
        $resteParType = [];
        foreach ($totauxDonsParType as $row) {
            $resteParType[$row['type_nom']] = (float)$row['total_dons'];
        }
        foreach ($totauxDistribParType as $row) {
            $type = $row['type_nom'];
            $resteParType[$type] = ($resteParType[$type] ?? 0) - (float)$row['total_distributions'];
        }

        $app->render('dashboard/layout', [
            'page'  => 'stat-don',
            'title' => 'Statistiques des dons',

            // Tous types
            'totaux_dons_par_type' => $totauxDonsParType,
            'totaux_distrib_par_type' => $totauxDistribParType,
            'reste_par_type' => $resteParType,
        ]);
    }
}
