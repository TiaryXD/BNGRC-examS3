<?php

namespace app\controllers;

use app\repositories\BesoinRepository;
use app\repositories\VilleRepository;
use app\repositories\TypeRepository;
use app\repositories\DistributionRepository;
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
            'page' => 'ajout-besoin',
            'title'  => 'Ajouter un besoin'
        ]);
    }



    /**
     * Enregistrer un besoin
     */
    public static function saveBesoinbyidville($app)
    {
        $repo = new BesoinRepository($app->db());

        $villeId       = isset($_POST['ville_id']) ? (int) $_POST['ville_id'] : null;
        $typeId        = isset($_POST['type_id']) ? (int) $_POST['type_id'] : null;
        $description   = trim($_POST['description'] ?? '');
        $quantite      = (float) ($_POST['quantite'] ?? 0);
        $unite         = trim($_POST['unite'] ?? '');
        $remarque      = trim($_POST['remarque'] ?? '');

        $prixUnitaire  = isset($_POST['prix_unitaire']) && $_POST['prix_unitaire'] !== ''
            ? (float) $_POST['prix_unitaire']
            : null;

        $errors = [];

        if (!$villeId)        $errors['ville_id'] = "Ville obligatoire";
        if (!$typeId)         $errors['type_id'] = "Type obligatoire";
        if (!$description)    $errors['description'] = "Description obligatoire";
        if ($quantite <= 0)   $errors['quantite'] = "Quantité invalide";
        if (!$unite)          $errors['unite'] = "Unité obligatoire";

        $typeRepo = new TypeRepository($app->db());
        $types = $typeRepo->get_type();
        $typeNom = null;

        foreach ($types as $t) {
            if ((int)$t['id'] === (int)$typeId) {
                $typeNom = $t['nom'];
                break;
            }
        }

        if ($typeNom === null) {
            $errors['type_id'] = "Type invalide";
        }

        if ($typeNom === 'Argent') {
            $prixUnitaire = null;
        } else {
            if ($prixUnitaire === null || $prixUnitaire <= 0) {
                $errors['prix_unitaire'] = "Prix unitaire obligatoire (doit être > 0) pour Nature/Matériaux";
            }
        }

        if (!empty($errors)) {
            $villeRepo = new VilleRepository($app->db());

            $app->render('dashboard/layout', [
                'errors' => $errors,
                'values' => $_POST,
                'villes' => $villeRepo->get_ville(),
                'types'  => $types,
                'page'   => 'ajout-besoin',
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
            $remarque ?: null,
            $prixUnitaire
        );

        header("Location: /ville/$villeId");
        exit;
    }


}
