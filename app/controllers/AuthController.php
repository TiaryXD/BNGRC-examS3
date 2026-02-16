<?php

namespace app\controllers;

use app\repositories\BesoinRepository;
use app\repositories\VilleRepository;
use app\repositories\TypeRepository;

class BesoinController
{
    /**
     * Afficher la liste des besoins
     */
    public static function showBesoin($app)
    {
        $repo = new BesoinRepository($app->getPDO());

        $besoins = $repo->get_besoin();

        $app->render('dashboard/besoin', [
            'besoins' => $besoins,
            'title'   => 'Liste des besoins'
        ]);
    }

    /**
     * Afficher formulaire création
     */
    public static function showCreate($app)
    {
        $villeRepo = new VilleRepository($app->getPDO());
        $typeRepo  = new TypeRepository($app->getPDO());

        $app->render('dashboard/create', [
            'villes' => $villeRepo->get_ville(),
            'types'  => $typeRepo->get_type(),
            'errors' => [],
            'values' => [],
            'title'  => 'Ajouter un besoin'
        ]);
    }

    /**
     * Enregistrer un besoin
     */
    public static function store($app)
    {
        $repo = new BesoinRepository($app->getPDO());

        $villeId     = $_POST['ville_id'] ?? null;
        $typeId      = $_POST['type_id'] ?? null;
        $description = trim($_POST['description'] ?? '');
        $quantite    = (float) ($_POST['quantite'] ?? 0);
        $unite       = trim($_POST['unite'] ?? '');
        $remarque    = trim($_POST['remarque'] ?? '');

        $errors = [];

        if (!$villeId)     $errors['ville_id'] = "Ville obligatoire";
        if (!$typeId)      $errors['type_id'] = "Type obligatoire";
        if (!$description) $errors['description'] = "Description obligatoire";
        if ($quantite <= 0) $errors['quantite'] = "Quantité invalide";
        if (!$unite)       $errors['unite'] = "Unité obligatoire";

        if (!empty($errors)) {
            $villeRepo = new VilleRepository($app->getPDO());
            $typeRepo  = new TypeRepository($app->getPDO());

            $app->render('dashboard/create', [
                'errors' => $errors,
                'values' => $_POST,
                'villes' => $villeRepo->get_ville(),
                'types'  => $typeRepo->get_type(),
                'title'  => 'Ajouter un besoin'
            ]);
            return;
        }

        $repo->insert_besoin(
            $villeId,
            $typeId,
            $description,
            $quantite,
            $unite,
            $remarque ?: null
        );

        header("Location: /dashboard/besoin");
        exit;
    }
}
