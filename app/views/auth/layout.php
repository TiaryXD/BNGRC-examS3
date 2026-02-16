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
    <title>Tak'ALO | <?= $title ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/auth-style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/icons/bootstrap-icons.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css" />
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/validation-ajax.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/page-transition.js" defer nonce="<?= formatText($cspNonce) ?>"></script>
</head>

<body>
<main class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-3 p-lg-0">
    <div class="card shadow-lg border-0 rounded-3 mx-auto" style="max-width: 950px; width: 100%;">
        <div class="row g-0 h-100">
            <aside class="col-lg-5 d-lg-flex flex-column justify-content-between p-5 text-white custom-sidebar">
                <header>
                    <div class="mb-4 d-flex align-items-center gap-1">
                        <img src="/assets/images/logo.png" style="width: 20px; height: 20px" alt="Logo">
                        <span class="fs-4">BNGRC</span>
                    </div>
                </header>
                <div>
                    <p class="small mb-0">On vous souhaite la</p>
                    <h2 class="fs-3">BIENVENUE</h2>
                </div>
            </aside>

            <section class="col-lg-7 p-4 p-md-5 auth-main">
                <div class="page page-enter">
                    <?php require 'pages/'. $page . '.php'; ?>
                </div>

                <footer class="col-12 pt-5">
                    <div class="d-flex gap-2 w-100 justify-content-center text-muted">
                        <p class="small"><i class="bi bi-c-circle"></i> 2026 - BNGRC</p>
                    </div>
                </footer>
            </section>
        </div>
    </div>
</main>
</body>

