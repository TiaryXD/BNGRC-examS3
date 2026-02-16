<?php
if (!isset($page)) {
    Flight::redirect('/404');
}

$page = $page ?? 'home';
$title = $title ?? '';
if(!isset($_SESSION['user'])) {
    $user = '';
} else {
    $user = $_SESSION['user'];
}

$cspNonce = Flight::get('csp_nonce');

$links = [
    ['href' => '/accueil', 'label' => 'Accueil'],
    ['href' => '/inventaire', 'label' => 'Inventaire'],
    ['href' => '/recherche', 'label' => 'Rechercher']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>BNGRC | <?= $title ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/home-style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/icons/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css" />
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bngrc-nav sticky-top">
  <div class="container">
    <a class="bngrc-brand navbar-brand" href="index.html">
      <span class="bngrc-logo">BNGRC</span>
      <span class="fw-semibold">Brandy 4201</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li class="nav-item"><a class="nav-link" href="index.html">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="#apropos">À propos</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="besoins.html">Besoins</a></li>
        <li class="nav-item"><a class="nav-link" href="villes.html">Villes sinistrées</a></li>

        <li class="nav-item only-guest">
          <a class="btn ms-lg-2" href="/login">Se connecter</a>
        </li>

        <li class="nav-item only-logged">
          <a class="btn btn-outline-light ms-lg-2" href="index.html">Se déconnecter</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container">
    <?php require 'pages/' . $page . '.php'; ?>
</main>

<footer class="bngrc-footer pt-4 pb-3" id="contact">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h6 class="text-uppercase fw-bold">Contact</h6>
        <ul class="list-unstyled small mb-0">
          <li>Email: contact@bngrc.mg</li>
          <li>Tél: +261 00 00 000 00</li>
          <li>Adresse: Antananarivo, Madagascar</li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="text-uppercase fw-bold">Réseaux sociaux</h6>
        <ul class="list-unstyled small mb-0">
          <li>Facebook: @BNGRC</li>
          <li>Instagram: @BNGRC</li>
          <li>X: @BNGRC</li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="text-uppercase fw-bold">ETU Groupe</h6>
        <ul class="list-unstyled small mb-0">
          <li>2804 - Finaritra</li>
          <li>4201 - Brandy</li>
          <li>4254 - Tiary</li>
        </ul>
      </div>
    </div>

    <hr class="border-light opacity-25 my-3">
    <div class="d-flex flex-wrap justify-content-between small">
      <span>© 2026 S3 — BNGRC</span>
    </div>
  </div>
</footer>
</body>
</html>