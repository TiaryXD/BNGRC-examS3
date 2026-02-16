<?php
$totaux_dons_par_type = $totaux_dons_par_type ?? [];
$totaux_distrib_par_type = $totaux_distrib_par_type ?? [];
$reste_par_type = $reste_par_type ?? [];

/**
 * Index distributions par type
 */
$distribIndex = [];
foreach ($totaux_distrib_par_type as $row) {
    $distribIndex[$row['don_nom']] = (float)$row['total_distributions'];
}
?>

<section class="section-padding">
  <div class="container">

    <!-- TITRE -->
    <div class="row">
      <div class="col-lg-10 col-12 text-center mx-auto mb-5">
        <h2 class="mb-2">Statistiques des dons</h2>
        <p class="text-muted mb-0">
          Situation des dons reçus et distribués par type.
        </p>
      </div>
    </div>

    <!-- TABLE -->
    <div class="row">
      <div class="col-12">
        <div class="custom-block p-4">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Détails par type</h5>
            <span class="small text-muted">Reçus • Distribués • Stock restant</span>
          </div>

          <?php if (empty($totaux_dons_par_type)): ?>
            <p class="text-muted mb-0">Aucune donnée disponible.</p>
          <?php else: ?>

          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Type</th>
                  <th class="text-end">Dons reçus</th>
                  <th class="text-end">Dons distribués</th>
                  <th class="text-end">Reste en stock</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($totaux_dons_par_type as $row): ?>
                  <?php
                    $type = $row['don_nom'];
                    $recus = (float)$row['total_dons'];
                    $distrib = (float)($distribIndex[$type] ?? 0);
                    $reste = $recus - $distrib;
                    $unite  = $row['unite'];

                    $url = '/distribuer?' . http_build_query([
                    'don'   => $type,
                    'unite' => $unite,
                    ]);
                  ?>
                  <tr>
                    <td class="fw-semibold">
                      <?= htmlspecialchars($type) ?>
                    </td>

                    <td class="text-end fw-bold">
                      <?= number_format($recus, 0, ',', ' ') ?>
                    </td>

                    <td class="text-end text-success fw-bold">
                      <?= number_format($distrib, 0, ',', ' ') ?>
                    </td>

                    <td class="text-end fw-bold <?= $reste >= 0 ? 'text-warning' : 'text-danger' ?>">
                      <?= number_format($reste, 0, ',', ' ') ?>
                    </td>

                    <td class="text-center">
                    <?php if ($reste > 0): ?>
                        <a href="<?= $url ?>" class="btn btn-sm btn-success">
                        <i class="bi-send"></i> Distribuer
                        </a>
                    <?php else: ?>
                        <a class="btn btn-sm btn-secondary disabled" aria-disabled="true">
                        <i class="bi-slash-circle"></i> Distribuer
                        </a>
                    <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <?php endif; ?>

        </div>
      </div>
    </div>

  </div>
</section>
