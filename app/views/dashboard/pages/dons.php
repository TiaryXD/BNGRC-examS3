<?php
// Données attendues depuis le controller
$dons = $dons ?? [];

// Stats simples
$totalDons = count($dons);
$totalQuantite = 0;

foreach ($dons as $d) {
    $totalQuantite += (float)($d['quantite'] ?? 0);
}
?>

<section class="section-padding">
  <div class="container">

    <div class="row mb-4">
        <div class="col-lg-8 col-12 mx-auto text-center">
            <h2 class="mb-2">Historique des dons</h2>
            <p class="text-muted mb-3">Suivi des dons reçus et informations associées.</p>

            <a href="/dons/ajout" class="custom-btn btn">
            <i class="bi-plus-circle me-2"></i>
            Entrer un don
            </a>
        </div>
    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="custom-block p-3 text-center">
          <small class="text-muted">Nombre de dons</small>
          <h4 class="mb-0"><?= (int)$totalDons ?></h4>
        </div>
      </div>

      <div class="col-md-4">
        <div class="custom-block p-3 text-center">
          <small class="text-muted">Dernière réception</small>
          <h4 class="mb-0">
            <?= !empty($dons[0]['date_reception']) ? htmlspecialchars($dons[0]['date_reception']) : '—' ?>
          </h4>
        </div>
      </div>
    </div>

    <!-- LISTE EN CARDS -->
    <div class="row">
      <?php if (empty($dons)): ?>
        <div class="col-12 text-center">
          <p class="text-muted">Aucun don enregistré.</p>
        </div>
      <?php else: ?>

        <?php foreach ($dons as $don): ?>
          <div class="col-lg-4 col-md-6 col-12 mb-4">

            <div class="custom-block-wrap">

              <div class="custom-block">
                <div class="custom-block-body">

                  <!-- Type -->
                  <small class="text-muted">
                    <i class="bi-tag me-1"></i>
                    <?= htmlspecialchars($don['type_nom'] ?? 'Type') ?>
                  </small>

                  <!-- Description -->
                  <h5 class="mb-2 mt-2">
                    <?= htmlspecialchars($don['description'] ?? '') ?>
                  </h5>

                  <!-- Infos -->
                  <ul class="list-unstyled small mb-3">
                    <li>
                      <i class="bi-box-seam text-warning"></i>
                      <strong>Quantité :</strong>
                      <?= number_format((float)($don['quantite'] ?? 0), 0, ',', ' ') ?>
                      <?= htmlspecialchars($don['unite'] ?? '') ?>
                    </li>

                    <li>
                      <i class="bi-calendar-event text-success"></i>
                      <strong>Réception :</strong>
                      <?= htmlspecialchars($don['date_reception'] ?? '') ?>
                    </li>

                    <?php if (!empty($don['source'])): ?>
                      <li>
                        <i class="bi-building text-primary"></i>
                        <strong>Source :</strong>
                        <?= htmlspecialchars($don['source']) ?>
                      </li>
                    <?php endif; ?>
                  </ul>

                  <?php if (!empty($don['remarque'])): ?>
                    <p class="text-muted small mb-0">
                      <?= htmlspecialchars($don['remarque']) ?>
                    </p>
                  <?php endif; ?>

                </div>
              </div>
            </div>

          </div>
        <?php endforeach; ?>

      <?php endif; ?>
    </div>

  </div>
</section>
