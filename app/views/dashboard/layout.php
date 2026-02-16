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
    ['href' => '/accueil', 'label' => 'Accueil']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>BNGRC | <?= $title ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-icons.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/templatemo-kind-heart-charity.css" />
</head>

<body id="section_1">

    <header class="site-header">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-12 d-flex flex-wrap">
                    <p class="d-flex me-4 mb-0">
                        <i class="bi-geo-alt me-2"></i>
                        Avaratra Antanimora Route Mausolée Antananarivo 101 - Madagascar
                    </p>

                    <p class="d-flex mb-0">
                        <i class="bi-envelope me-2"></i>

                        <a href="mailto:info@company.com">
                            bngrc@email.com
                        </a>
                    </p>
                </div>

                <div class="col-lg-3 col-12 ms-auto d-lg-block d-none">
                    <ul class="social-icon">
                        <li class="social-icon-item">
                            <a href="https://twitter.com/bngrcmada" class="social-icon-link bi-twitter"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="https://www.facebook.com/BNGRCMID/" class="social-icon-link bi-facebook"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="https://www.instagram.com/bngrc_madagascar/" class="social-icon-link bi-instagram"></a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="/assets/images/logo.png" class="logo img-fluid" alt="Kind Heart Charity">
                <span>
                    BNGRC
                    <small>Bureau National de Gestion des Risques et des Catastrophes</small>
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="/">Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_2">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_3">Causes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_4">Volunteer</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link click-scroll dropdown-toggle" href="#section_5"
                            id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">News</a>

                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                            <li><a class="dropdown-item" href="news.html">News Listing</a></li>

                            <li><a class="dropdown-item" href="news-detail.html">News Detail</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_6">Contact</a>
                    </li>

                    <?php if (empty($user)): ?>
                        <li class="nav-item ms-3">
                            <a class="nav-link custom-btn custom-border-btn btn" href="/login">
                                Se connecter
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-3">
                            <a class="nav-link custom-btn custom-border-btn btn" href="/logout">
                                Se déconnecter
                            </a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
    <div class="container">
        <?php require 'pages/' . $page . '.php'; ?>
    </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-12 mb-4">
                    <img src="/assets/images/logo.png" class="logo img-fluid" alt="">
                </div>

                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <h5 class="site-footer-title mb-3">Liens rapides</h5>

                    <ul class="footer-menu">
                        <li class="footer-menu-item"><a href="/" class="footer-menu-link">Accueil</a></li>

                        <li class="footer-menu-item"><a href="/besoins" class="footer-menu-link">Besoins</a></li>

                        <li class="footer-menu-item"><a href="/villes" class="footer-menu-link">Villes</a></li>

                        <li class="footer-menu-item"><a href="/dons" class="footer-menu-link">Dons</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 col-12 mx-auto">
                    <h5 class="site-footer-title mb-3">Contact</h5>

                    <p class="text-white d-flex mb-2">
                        <i class="bi-telephone me-2"></i>

                        <a href="tel:+261340548068" class="site-footer-link">
                            +261 34 05 480 68
                        </a>
                    </p>

                    <p class="text-white d-flex">
                        <i class="bi-envelope me-2"></i>

                        <a href="mailto:info@yourgmail.com" class="site-footer-link">
                            bngrc@email.com
                        </a>
                    </p>

                    <p class="text-white d-flex mt-3">
                        <i class="bi-geo-alt me-2"></i>
                        Avaratra Antanimora Route Mausolée Antananarivo 101 - Madagascar
                    </p>

                    <a href="https://g.page/bngrc-antanimora" class="custom-btn btn mt-3">Voir direction</a>
                </div>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-md-7 col-12">
                        <p class="copyright-text mb-0">Copyright © 2026 <a href="#">BNGRC</a>
                        <br>
                        ETU 2801 - 4201 - 4254
                        </p>
                    </div>

                    <div class="col-lg-6 col-md-5 col-12 d-flex justify-content-center align-items-center mx-auto">
                        <ul class="social-icon">
                            <li class="social-icon-item">
                                <a href="https://twitter.com/bngrcmada" class="social-icon-link bi-twitter"></a>
                            </li>

                            <li class="social-icon-item">
                                <a href="https://www.facebook.com/BNGRCMID/#" class="social-icon-link bi-facebook"></a>
                            </li>

                            <li class="social-icon-item">
                                <a href="https://www.instagram.com/bngrc_madagascar/" class="social-icon-link bi-instagram"></a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </footer>

    <script src="/assets/js/jquery.min.js" nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/bootstrap.min.js" nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/jquery.sticky.js" nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/click-scroll.js" nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/counter.js" nonce="<?= formatText($cspNonce) ?>"></script>
    <script src="/assets/js/custom.js" nonce="<?= formatText($cspNonce) ?>"></script>
</body>
</html>