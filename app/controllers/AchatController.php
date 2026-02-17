<?php

namespace app\controllers;

use app\repositories\AchatRepository;
use app\repositories\VilleRepository;
use app\repositories\BesoinRepository;
use Flight;

class AchatController
{
    public static function formAchat($app)
    {
        $villeRepo = new VilleRepository($app->db());
        $besoinRepo = new BesoinRepository($app->db());
        $achatRepo = new AchatRepository($app->db());

        $besoins = $besoinRepo->get_besoin();

        $app->render('dashboard/layout', [
            'villes' => $villeRepo->get_ville(),
            'besoins' => $besoins,
            'argent_disponible' => $achatRepo->getArgentDisponible(),
            'errors' => [],
            'values' => [],
            'page' => 'ajout-achat',
            'title' => 'Faire un achat'
        ]);
    }

    public static function saveachat($app)
    {
        $achatRepo = new AchatRepository($app->db());

        $villeId   = isset($_POST['ville_id']) ? (int)$_POST['ville_id'] : null;
        $besoinId  = isset($_POST['besoin_id']) ? (int)$_POST['besoin_id'] : null;
        $quantite  = (float)($_POST['quantite'] ?? 0);
        $remarque  = trim($_POST['remarque'] ?? '');

        $errors = [];

        if (!$villeId)  $errors['ville_id'] = "Ville obligatoire";
        if (!$besoinId) $errors['besoin_id'] = "Besoin obligatoire";
        if ($quantite <= 0) $errors['quantite'] = "Quantité invalide";

        // Charger info besoin
        $besoinInfo = null;
        if ($besoinId) {
            $besoinInfo = $achatRepo->getBesoinInfo($besoinId);
            if (!$besoinInfo) {
                $errors['besoin_id'] = "Besoin introuvable";
            }
        }

        // Règles V2 : achat seulement pour Nature/Matériaux + prix obligatoire
        if ($besoinInfo) {
            $typeNom = $besoinInfo['type_nom'];
            $prixUnitaire = $besoinInfo['prix_unitaire'];

            if ($typeNom === 'Argent') {
                $errors['besoin_id'] = "Impossible d'acheter un besoin de type Argent";
            }

            if ($prixUnitaire === null || (float)$prixUnitaire <= 0) {
                $errors['prix_unitaire'] = "Prix unitaire manquant pour ce besoin";
            }

            // Cohérence ville (optionnel mais très propre)
            if ($villeId && (int)$besoinInfo['ville_id'] !== (int)$villeId) {
                $errors['ville_id'] = "Ce besoin n'appartient pas à la ville sélectionnée";
            }
        }

        // Calcul montant + contrôle argent disponible
        $montantTotal = 0.0;
        $argentDisponible = $achatRepo->getArgentDisponible();

        if ($besoinInfo && empty($errors)) {
            $montantTotal = $quantite * (float)$besoinInfo['prix_unitaire'];

            if ($montantTotal > $argentDisponible) {
                $errors['argent'] = "Fonds insuffisants : disponible = {$argentDisponible} Ar";
            }
        }

        if (!empty($errors)) {
            $villeRepo = new VilleRepository($app->db());
            $besoinRepo = new BesoinRepository($app->db());

            $app->render('dashboard/layout', [
                'villes' => $villeRepo->get_ville(),
                'besoins' => $besoinRepo->get_besoin(),
                'argent_disponible' => $argentDisponible,
                'errors' => $errors,
                'values' => $_POST,
                'page' => 'ajout-achat',
                'title' => 'Faire un achat'
            ]);
            return;
        }

        // created_by (si tu as une session admin)
        $createdBy = $_SESSION['admin_id'] ?? null;

        $achatRepo->insert_achat(
            $villeId,
            $besoinId,
            $quantite,
            (float)$besoinInfo['prix_unitaire'],
            $montantTotal,
            $remarque ?: null,
            $createdBy ? (int)$createdBy : null
        );

        header("Location: /achat");
        exit;
    }

    // Liste achats (filtre ville)
    public static function getlisteachat($app)
    {
        $achatRepo = new AchatRepository($app->db());
        $villeRepo = new VilleRepository($app->db());

        $villeId = isset($_GET['ville_id']) && $_GET['ville_id'] !== '' ? (int)$_GET['ville_id'] : null;

        $app->render('dashboard/layout', [
            'villes' => $villeRepo->get_ville(),
            'achats' => $achatRepo->get_achats($villeId),
            'argent_disponible' => $achatRepo->getArgentDisponible(),
            'selected_ville_id' => $villeId,
            'page' => 'achat',
            'title' => 'Liste des achats'
        ]);
    }
}
