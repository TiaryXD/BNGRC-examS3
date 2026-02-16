<?php

namespace app\controllers;

use app\repositories\DonRepository;
use app\repositories\TypeRepository;
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

    // Afficher le formulaire d'ajout de don
    public static function createdon($app)
    {
        $typeRepo = new TypeRepository($app->db());

        $app->render('dashboard/layout', [
            'types'  => $typeRepo->get_type(),
            'page'   => 'ajout-don',
            'title'  => 'Ajouter un don'
        ]);
    }

    // Enregistrer un don
    public static function savedon($app)
    {
        $repo = new DonRepository($app->db());

        $typeId        = isset($_POST['type_id']) ? (int) $_POST['type_id'] : null;
        $description   = trim($_POST['description'] ?? '');
        $quantite      = (float) ($_POST['quantite'] ?? 0);
        $unite         = trim($_POST['unite'] ?? '');
        $dateReception = $_POST['date_reception'] ?? null;
        $source        = trim($_POST['source'] ?? '');
        $remarque      = trim($_POST['remarque'] ?? '');

        $errors = [];

        if (!$typeId)            $errors['type_id'] = "Type obligatoire";
        if (!$description)       $errors['description'] = "Description obligatoire";
        if ($quantite <= 0)      $errors['quantite'] = "Quantité invalide";
        if (!$unite)             $errors['unite'] = "Unité obligatoire";
        if (!$dateReception)     $errors['date_reception'] = "Date de réception obligatoire";

        if (!empty($errors)) {
            $typeRepo = new TypeRepository($app->db());

            $app->render('dashboard/layout', [
                'errors' => $errors,
                'values' => $_POST,
                'types'  => $typeRepo->get_type(),
                'page'   => 'ajout-don',
                'title'  => 'Ajouter un don'
            ]);
            return;
        }

        $repo->insert_don(
            $typeId,
            $description,
            $quantite,
            $unite,
            $dateReception,
            $source ?: null,
            $remarque ?: null
        );

        header("Location: /don");
        exit;
    }
}
