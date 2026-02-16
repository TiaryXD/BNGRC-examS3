<?php

namespace app\controllers;

use app\repositories\BesoinRepository;
use app\repositories\VilleRepository;
use app\repositories\TypeRepository;
use Flight;

class BesoinController
{
    /**
     * Afficher la liste des besoins
     */
    public static function showBesoin($app)
    {
        $repo = new BesoinRepository($app->db());

        $besoins = $repo->get_besoin();

        $app->render('dashboard/layout', [
            'besoins' => $besoins,
            'page' => 'besoin',
            'title'   => 'Liste des besoins'
        ]);
    }

        public static function showVille($app)
    {
        $repo = new VilleRepository($app->db());

        $villes = $repo->get_ville();

        $app->render('dashboard/layout', [
            'villes' => $villes,
            'page' => 'ville',
            'title'   => 'Liste des villes'
        ]);
    }

    public static function showVilleById($app, $id)
    {
        $repo = new VilleRepository($app->db());

        $ville = $repo->getVilleById((int)$id);
        $repo = new BesoinRepository($app->db());
        $besoins = $repo->getbesoinbyidville((int)$id);
        $repo = new DistributionRepository($app->db());
        $distribution = $repo->getDistributionsByVilleId((int)$id);

        $app->render('dashboard/layout', [
            'ville' => $ville,
            'besoin'=> $besoins,
            'distribution' => $distribution,
            'page'  => 'ville-detail',
            'title' => 'Détail de la ville'
        ]);
    }

    /**
     * Afficher formulaire création
     */
    public static function showCreate($app)
    {
        $villeRepo = new VilleRepository($app->db());
        $typeRepo  = new TypeRepository($app->db());

        $app->render('dashboard/layout', [
            'villes' => $villeRepo->get_ville(),
            'types'  => $typeRepo->get_type(),
            'errors' => [],
            'values' => [],
            'page' => 'besoin',
            'title'  => 'Ajouter un besoin'
        ]);
    }

    /**
     * Enregistrer un besoin
     */
    public static function store($app)
    {
        $repo = new BesoinRepository($app->db());

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
            $villeRepo = new VilleRepository($app->db());
            $typeRepo  = new TypeRepository($app->db());

            $app->render('dashboard/layout', [
                'errors' => $errors,
                'values' => $_POST,
                'villes' => $villeRepo->get_ville(),
                'types'  => $typeRepo->get_type(),
                'page' => 'besoin',
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
