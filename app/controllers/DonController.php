<?php

namespace app\controllers;

use app\repositories\DonRepository;
use app\repositories\TypeRepository;
use app\repositories\VilleRepository;
use app\repositories\BesoinRepository;
use app\repositories\DistributionRepository;
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

    public static function createdon($app)
    {
        $typeRepo = new TypeRepository($app->db());

        $app->render('dashboard/layout', [
            'types'  => $typeRepo->get_type(),
            'page'   => 'ajout-don',
            'title'  => 'Ajouter un don'
        ]);
    }

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

        header("Location: /dons");
        exit;
    }

    public function getRestePourDon(string $donNom, string $unite): float
    {
        $sqlRecus = "
        SELECT COALESCE(SUM(quantite),0) AS total
        FROM dons
        WHERE LOWER(TRIM(description)) = LOWER(TRIM(:don))
            AND unite = :unite
        ";
        $st = $this->pdo->prepare($sqlRecus);
        $st->execute([':don' => $donNom, ':unite' => $unite]);
        $recus = (float)$st->fetch(\PDO::FETCH_ASSOC)['total'];

        $sqlDistrib = "
        SELECT COALESCE(SUM(quantite),0) AS total
        FROM distributions
        WHERE LOWER(TRIM(description)) = LOWER(TRIM(:don))
        ";
        $st = $this->pdo->prepare($sqlDistrib);
        $st->execute([':don' => $donNom]);
        $distrib = (float)$st->fetch(\PDO::FETCH_ASSOC)['total'];

        return $recus - $distrib;
    }

    public static function showDistribuerForm($app)
    {
        $q = Flight::request()->query;

        $donNom = trim((string)($q['don'] ?? ''));
        $unite  = trim((string)($q['unite'] ?? ''));
        $villeId = (int)($q['ville_id'] ?? 0);

        if ($donNom === '' || $unite === '') {
            Flight::redirect('/stat-don?error=missing_params');
            return;
        }

        $donRepo = new DonRepository($app->db());
        $resteStock = $donRepo->getRestePourDon($donNom, $unite);
        $typeRepo = new TypeRepository($app->db());
        $typeId = $typeRepo->getTypeIdForDon($donNom);  

        $villeRepo = new VilleRepository($app->db());
        $besoinRepo = new BesoinRepository($app->db());

        $besoins = [];
        if ($villeId > 0 && $typeId) {
            $besoins = $besoinRepo->getBesoinsByVilleAndType($villeId, $typeId);
        }

        $app->render('dashboard/layout', [
            'page'   => 'distribuer-don',
            'title'  => 'Distribuer un don',

            'don_nom' => $donNom,
            'unite'   => $unite,
            'reste_stock' => $resteStock,
            'type_id' => $typeId,

            'villes'  => $villeRepo->get_ville(),
            'besoins' => $besoins,

            'errors' => [],
            'values' => [
                'ville_id' => $villeId ?: '',
                'besoin_id' => '',
                'quantite' => '',
                'remarque' => ''
            ],
        ]);
    }


    public static function saveDistribution($app)
    {
        $donNom  = trim($_POST['don_nom'] ?? '');
        $unite   = trim($_POST['unite'] ?? '');
        $villeId = (int)($_POST['ville_id'] ?? 0);
        $quantite = (float)($_POST['quantite'] ?? 0);
        $remarque = trim($_POST['remarque'] ?? '');

        $errors = [];

        if ($donNom === '') $errors['don_nom'] = "Don obligatoire";
        if ($unite === '') $errors['unite'] = "Unité obligatoire";
        if ($villeId <= 0) $errors['ville_id'] = "Ville obligatoire";
        if ($quantite <= 0) $errors['quantite'] = "Quantité invalide";

        $donRepo = new DonRepository($app->db());
        $resteStock = $donRepo->getRestePourDon($donNom, $unite);

        if ($quantite > $resteStock) {
            $errors['quantite'] = "Stock insuffisant (reste: " . number_format($resteStock, 0, ',', ' ') . " $unite)";
        }

        $besoinRepo = new BesoinRepository($app->db());

        $besoin = $besoinRepo->getBesoinByVilleAndDonNom($villeId, $donNom);

        if (!$besoin) {
            $errors['ville_id'] = "Cette ville n'a pas de besoin enregistré pour : $donNom";
        } else {
            if ($besoin['unite'] !== $unite) {
                $errors['quantite'] = "Unité incompatible (besoin: {$besoin['unite']}, don: $unite)";
            }

            $quantiteBesoin = (float)$besoin['quantite'];
            $dejaDistribue  = $besoinRepo->getTotalDistribuePourBesoin((int)$besoin['id']);
            $resteBesoin    = max(0, $quantiteBesoin - $dejaDistribue);

            if ($quantite > $resteBesoin) {
                $errors['quantite'] = "Besoin insuffisant. Reste à couvrir: "
                    . number_format($resteBesoin, 0, ',', ' ') . " {$besoin['unite']}";
            }
        }

        if (!empty($errors)) {
            $villeRepo = new VilleRepository($app->db());

            $app->render('dashboard/layout', [
                'page'   => 'distribuer-don',
                'title'  => 'Distribuer un don',
                'don_nom' => $donNom,
                'unite'   => $unite,
                'reste_stock' => $resteStock,
                'villes'  => $villeRepo->get_ville(),
                'errors'  => $errors,
                'values'  => $_POST,
            ]);
            return;
        }

        $besoinId = (int)$besoin['id'];

        $distRepo = new DistributionRepository($app->db());
        $createdBy = $_SESSION['user']['id'] ?? null;

        $distRepo->insertDistribution(
            $besoinId,
            $donNom,
            $quantite,
            $remarque ?: null,
            $createdBy
        );

        Flight::redirect('/ville/' . $villeId);
    }




}
