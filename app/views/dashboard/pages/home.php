<?php
    if(!isset($_SESSION['user'])) {
    $user = '';
} else {
    $user = $_SESSION['user'];
}
?>
<main class="py-4">
  <div class="container">

    <section class="hero-section hero-section-full-height">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12 col-12 p-0">
                    <div id="hero-slide" class="carousel carousel-fade slide" data-bs-ride="carousel">

                        <div class="carousel-inner">

                            <!-- SLIDE 1 -->
                            <div class="carousel-item active">
                                <img src="/assets/images/slide/solidarite.jpg"
                                    class="carousel-image img-fluid" alt="Aide humanitaire">

                                <div class="carousel-caption d-flex flex-column justify-content-end">
                                    <h1>Solidarité</h1>

                                    <p>
                                        Coordination nationale de l’aide aux populations sinistrées.
                                    </p>
                                </div>
                            </div>

                            <!-- SLIDE 2 -->
                            <div class="carousel-item">
                                <img src="/assets/images/slide/besoin.jpg"
                                    class="carousel-image img-fluid" alt="Distribution de dons">

                                <div class="carousel-caption d-flex flex-column justify-content-end">
                                    <h1>Besoins • Dons</h1>

                                    <p>
                                        Suivi des ressources et répartition équitable des aides.
                                    </p>
                                </div>
                            </div>

                            <!-- SLIDE 3 -->
                            <div class="carousel-item">
                                <img src="/assets/images/slide/prevention.jpg"
                                    class="carousel-image img-fluid" alt="Résilience communautaire">

                                <div class="carousel-caption d-flex flex-column justify-content-end">
                                    <h1>Prévention</h1>

                                    <p>
                                        Renforcer la capacité des communautés face aux catastrophes.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- CONTROLS -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#hero-slide"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#hero-slide"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-10 col-12 text-center mx-auto">
                    <h2 class="mb-5">Plateforme Nationale de Gestion des Urgences</h2>
                </div>

                <!-- BESOIN -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <div class="featured-block d-flex justify-content-center align-items-center">
                        <a href="/besoins" class="d-block text-center">
                            <img src="/assets/images/icons/hands.png"
                                class="featured-block-image img-fluid" alt="Besoins">

                            <p class="featured-block-text">
                                Suivre les <strong>Besoins</strong>
                            </p>
                        </a>
                    </div>
                </div>

                <!-- VILLES -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                    <div class="featured-block d-flex justify-content-center align-items-center">
                        <a href="/villes" class="d-block text-center">
                            <img src="/assets/images/icons/heart.png"
                                class="featured-block-image img-fluid" alt="Villes sinistrées">

                            <p class="featured-block-text">
                                Villes <strong>Sinistrées</strong>
                            </p>
                        </a>
                    </div>
                </div>

                <!-- DONS -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                    <div class="featured-block d-flex justify-content-center align-items-center">
                        <a href="/dons" class="d-block text-center">
                            <img src="/assets/images/icons/receive.png"
                                class="featured-block-image img-fluid" alt="Dons">

                            <p class="featured-block-text">
                                Gérer les <strong>Dons</strong>
                            </p>
                        </a>
                    </div>
                </div>

                <!-- COORDINATION -->
                <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                    <div class="featured-block d-flex justify-content-center align-items-center">
                        <a href="/accueil" class="d-block text-center">
                            <img src="/assets/images/icons/scholarship.png"
                                class="featured-block-image img-fluid" alt="Coordination">

                            <p class="featured-block-text">
                                <strong>Distribution</strong> des dons
                            </p>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


  </div>
</main>


