<?php

namespace app\controllers;
use app\repositories\VenteRepository;

use Flight;

class VenteController
{
    public static function showVente($app) {
        $repo = new \app\repositories\VenteRepository($app->db());

        $app->render('dashboard/layout', [
            'page' => 'vente',
            'title' => 'Vente des dons',
            'reduction_pct' => $repo->getReductionPct(),
            'dons' => $repo->getDonsVendables()
        ]);
    }

    public static function saveVente($app)
    {

        $pdo  = $app->db();
        $repo = new \app\repositories\VenteRepository($pdo);

        $donId = (int)($_POST['don_id'] ?? 0);
        $qte   = (float)($_POST['quantite'] ?? 0);
        $rem   = trim($_POST['remarque'] ?? '');

        if ($donId <= 0 || $qte <= 0) {
            $app->render('dashboard/layout', [
                'page' => 'vente',
                'title' => 'Vente des dons',
                'errors' => ['global' => "Don ou quantité invalide."],
                'dons' => $repo->getDonsVendables(),
                'reduction_pct' => $repo->getReductionPct(),
            ]);
            return;
        }

        $don = $repo->getDonById($donId);
        if (!$don) {
            $app->render('dashboard/layout', [
                'page' => 'vente',
                'title' => 'Vente des dons',
                'errors' => ['global' => "Don introuvable."],
                'dons' => $repo->getDonsVendables(),
                'reduction_pct' => $repo->getReductionPct(),
            ]);
            return;
        }

        if ($qte > (float)$don['quantite']) {
            $app->render('dashboard/layout', [
                'page' => 'vente',
                'title' => 'Vente des dons',
                'errors' => ['global' => "Quantité supérieure au stock."],
                'dons' => $repo->getDonsVendables(),
                'reduction_pct' => $repo->getReductionPct(),
            ]);
            return;
        }

        if (!$repo->isDonVendable($donId)) {
            $app->render('dashboard/layout', [
                'page' => 'vente',
                'title' => 'Vente des dons',
                'errors' => ['global' => "Ce don ne peut pas être vendu (besoin existant ou type Argent)."],
                'dons' => $repo->getDonsVendables(),
                'reduction_pct' => $repo->getReductionPct(),
            ]);
            return;
        }

        $pu = (float)$repo->getPrixUnitairePourDon($don['type_id'], $don['description']);
        if ($pu <= 0) {
            $app->render('dashboard/layout', [
                'page' => 'vente',
                'title' => 'Vente des dons',
                'errors' => ['global' => "Prix unitaire introuvable pour ce don (vérifie besoins.prix_unitaire)."],
                'dons' => $repo->getDonsVendables(),
                'reduction_pct' => $repo->getReductionPct(),
            ]);
            return;
        }

        $reduc = (float)$repo->getReductionPct();
        $puFinal = $pu * (1 - ($reduc / 100));
        $montant = $puFinal * $qte;

        $pdo->beginTransaction();
        try {
            $repo->decrementStockDon($donId, $qte);

            $repo->insertVente([
                'don_id' => $donId,
                'quantite' => $qte,
                'prix_unitaire' => $pu,
                'reduction_pct' => $reduc,
                'prix_unitaire_final' => $puFinal,
                'montant_total' => $montant,
                'remarque' => $rem ?: null,
                'created_by' => (int)($_SESSION['user']['id'] ?? null),
            ]);

            $pdo->commit();
            Flight::redirect('/vente?success=1');
        } catch (\Throwable $e) {
            $pdo->rollBack();

            $app->render('dashboard/layout', [
                'page' => 'vente',
                'title' => 'Vente des dons',
                'errors' => ['global' => "Erreur vente : " . $e->getMessage()],
                'dons' => $repo->getDonsVendables(),
                'reduction_pct' => $repo->getReductionPct(),
            ]);
        }
    }



    
}
