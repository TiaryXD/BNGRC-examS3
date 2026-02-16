<?php
$cspNonce = Flight::get('csp_nonce');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Tak'ALO | Erreur 404</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/assets/css/error-style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/icons/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css" />
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
</head>

<body class="d-flex flex-column min-vh-100">

<div class="error-page d-flex flex-column align-items-center justify-content-center text-center">
    <div class="error-code-container">
        <h1 class="error-code">404</h1>
        <div class="error-divider"></div>
    </div>

    <div class="error-content mt-2">
        <h2 class="fw-bold text-white uppercase-text mb-5">PAGE INTROUVABLE<span class="text-lime">.</span></h2>

        <a href="/accueil" class="btn btn-lime px-5 py-3 shadow-lg text-decoration-none">
            RETOURNER À L'ACCUEIL
        </a>
    </div>

    <div class="error-watermark">Tak'ALO.</div>
</div>

</body>
</html>