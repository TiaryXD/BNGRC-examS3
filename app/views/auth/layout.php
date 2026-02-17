<?php
    $title = $title ?? 'Authentification';

    if (!isset($page)) {
        Flight::redirect('/404');
    }

    if (isset($_SESSION['user'])) {
        Flight::redirect('/home');
    }

    $cspNonce = Flight::get('csp_nonce');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <title>BNGRC | <?= htmlspecialchars($title) ?></title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="/assets/images/logo.png" />

  <link rel="stylesheet" href="/assets/icons/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/templatemo-kind-heart-charity.css" />
  <script src="/assets/js/validation-ajax.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
  


  <script src="/assets/js/bootstrap.bundle.min.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<main class="flex-grow-1">
  <?php require __DIR__ . '/pages/' . $page . '.php'; ?>
</main>

<footer class="bngrc-footer pt-4 pb-3 mt-auto" id="contact">
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
      <span>© <?= date('Y') ?> S3 — BNGRC</span>
    </div>
  </div>
</footer>

</body>
</html>

