<?php
    if(!isset($_SESSION['user'])) {
    $user = '';
} else {
    $user = $_SESSION['user'];
}
?>
<main class="py-4">
  <div class="container">

    <div class="p-4 p-md-5 hero">
      <div class="row align-items-center g-4">
        <div class="col-md-7">
          <h1 class="display-6 fw-bold">Solidarité & Urgence — Plateforme BNGRC</h1>
          <p class="lead mb-3">
            Suivez les besoins des villes sinistrées, consultez les priorités et contribuez avec des dons.
          </p>

          <div class="only-logged d-flex flex-wrap gap-2">
            <a class="btn btn-bngrc btn-lg" href="admin-dons.html">Gérer les dons</a>
          </div>
        </div>

        <div class="col-md-5">
          <img class="img-fluid rounded-4 border" src="/assets/images/hero.jpg" alt="BNGRC - Urgence">
        </div>
      </div>
    </div>

    <div class="row g-4 mt-1">
      <div class="col-md-6 col-lg-3">
        <div class="section-card h-100">
          <img class="w-100 card-img-fixed" src="/assets/images/besoin.jpg" alt="Besoins">
          <div class="p-3">
            <h5 class="fw-bold">Voir les besoins</h5>
            <p class="small text-muted mb-3">Consultez la liste des besoins prioritaires par ville.</p>
            <a class="btn btn-outline-dark w-100" href="besoins.html">Voir les besoins</a>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="section-card h-100">
          <img class="w-100 card-img-fixed" src="/assets/images/villes.png" alt="Villes sinistrées">
          <div class="p-3">
            <h5 class="fw-bold">Villes sinistrées</h5>
            <p class="small text-muted mb-3">Découvrez les zones touchées et leurs besoins associés.</p>
            <a class="btn btn-outline-dark w-100" href="villes.html">Voir les villes</a>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="section-card h-100">
          <img class="w-100 card-img-fixed" src="/assets/images/don.jpg" alt="Insérer un don">
          <div class="p-3">
            <h5 class="fw-bold">Insérer un don</h5>
            <p class="small text-muted mb-3">Ajout/distribution des dons côté admin.</p>
            <a class="btn btn-outline-dark w-100" href="admin-dons.html">Aller</a>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="section-card h-100">
          <img class="w-100 card-img-fixed" src="/assets/images/apropos.jpg" alt="À propos">
          <div class="p-3">
            <h5 class="fw-bold">À propos</h5>
            <p class="small text-muted mb-3">Plateforme pédagogique — BNGRC.</p>
            <a class="btn btn-outline-dark w-100" href="#apropos">Lire</a>
          </div>
        </div>
      </div>
    </div>

    <section class="mt-5" id="apropos">
      <div class="row g-4 align-items-center">
        <div class="col-md-6">
          <h2 class="fw-bold">À propos</h2>
          <p class="text-muted">
            Cette interface présente une plateforme de suivi des besoins et de distribution des dons
            (version front-end Bootstrap).
          </p>
        </div>
        <div class="col-md-6">
          <img class="img-fluid rounded-4 border" src="/assets/images/about.jpg" alt="À propos">
        </div>
      </div>
    </section>

  </div>
</main>


