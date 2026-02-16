<?php

namespace app\controllers;

use app\repositories\BesoinRepository;
use app\repositories\VilleRepository;
use app\repositories\TypeRepository;
use PDO;

class BesoinController
{
    private BesoinRepository $besoinRepository;
    private VilleRepository $villeRepository;
    private TypeRepository $typeRepository;

    public function __construct(PDO $pdo)
    {
        $this->besoinRepository = new BesoinRepository($pdo);
        $this->villeRepository  = new VilleRepository($pdo);
        $this->typeRepository   = new TypeRepository($pdo);

        $this->checkAuth();
    }

    private function checkAuth()
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: /login");
            exit;
        }
    }

    /* Afficher la liste des besoins */
    public function showBesoin()
    {
        $besoins = $this->besoinRepository->get_besoin();

        require __DIR__ . '/../views/besoins/index.php';
    }

    /* Afficher le formulaire */
    public function createBesoin()
    {
        $villes = $this->villeRepository->findAllWithRegion();
        $types  = $this->typeRepository->findAll();

        require __DIR__ . '/../views/besoins/create.php';
    }

    /* Enregistrer un besoin */
    public function storeBesoin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /besoins");
            exit;
        }

        $villeId     = $_POST['ville_id'] ?? null;
        $typeId      = $_POST['type_id'] ?? null;
        $description = trim($_POST['description'] ?? '');
        $quantite    = (float) ($_POST['quantite'] ?? 0);
        $unite       = trim($_POST['unite'] ?? '');
        $remarque    = trim($_POST['remarque'] ?? '');

        if (!$villeId || !$typeId || empty($description) || $quantite <= 0 || empty($unite)) {
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            header("Location: /besoins/create");
            exit;
        }

        $this->besoinRepository->insert_besoin(
            $villeId,
            $typeId,
            $description,
            $quantite,
            $unite,
            $remarque ?: null
        );

        $_SESSION['success'] = "Besoin ajouté avec succès.";
        header("Location: /besoins");
        exit;
    }
}
