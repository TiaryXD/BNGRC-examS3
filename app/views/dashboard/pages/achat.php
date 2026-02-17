<?php
$achats = $achats ?? [];
$villes = $villes ?? [];
$argent_disponible = $argent_disponible ?? 0;
$selected_ville_id = $selected_ville_id ?? null;

$totalAchats = count($achats);
$totalMontant = 0;
$derniereDate = '—';

foreach ($achats as $a) {
    $totalMontant += (float)($a['montant_total'] ?? 0);
}

if (!empty($achats[0]['date_achat'])) {
    $derniereDate = $achats[0]['date_achat'];
}
?>

<section class="section-padding">
  <div class="container">

    <!-- TITRE -->
    <div class="row">
      <div class="col-lg-10 col-12 text-center mx-auto mb-5">
        <h2 class="mb-2">Historique des achats</h2>
        <p class="text-muted mb-0">
          Achats réalisés via les dons en <strong>Argent</strong> pour couvrir les besoins <strong>Nature</strong> / <strong>Matériaux</strong>.
        </p>

        <div class="mt-3 d-flex justify-content-center gap-2 flex-wrap">
          <a href="/achat/form-achat" class="custom-btn btn">
            <i class="bi-plus-circle me-2"></i> Faire un achat
          </a>

          <div class="d-flex align-items-center gap-2">
            <select id="villeFilter" class="form-select">
              <option value="">Toutes les villes</option>

              <?php foreach ($villes as $v): ?>
                <option value="<?= (int)$v['id'] ?>">
                  <?= htmlspecialchars($v['nom']) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <button type="button" id="resetFilter" class="btn btn-outline-secondary">
              <i class="bi-x-circle"></i>
            </button>
          </div>

        </div>
      </div>
    </div>

    <!-- STATS (comme ton style) -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="custom-block p-3 text-center">
          <small class="text-muted">Nombre d’achats</small>
          <h4 class="mb-0"><?= (int)$totalAchats ?></h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="custom-block p-3 text-center">
          <small class="text-muted">Total achats</small>
          <h4 class="mb-0"><?= number_format((float)$totalMontant, 0, ',', ' ') ?> Ar</h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="custom-block p-3 text-center">
          <small class="text-muted">Dernier achat</small>
          <h4 class="mb-0"><?= htmlspecialchars($derniereDate) ?></h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="custom-block p-3 text-center">
          <small class="text-muted">Argent disponible</small>
          <h4 class="mb-0"><?= number_format((float)$argent_disponible, 0, ',', ' ') ?> Ar</h4>
        </div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="row">
      <div class="col-12">
        <div class="custom-block p-4">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Liste des achats</h5>
            <span class="small text-muted">Ville • Besoin • Quantité • Montant</span>
          </div>

          <?php if (empty($achats)): ?>
            <p class="text-muted mb-0">Aucun achat enregistré.</p>
          <?php else: ?>

          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Date</th>
                  <th>Ville</th>
                  <th>Type</th>
                  <th>Besoin</th>
                  <th class="text-end">Quantité</th>
                  <th class="text-end">Prix unitaire</th>
                  <th class="text-end">Montant</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($achats as $a): ?>
                  <?php
                    $quantite = (float)($a['quantite'] ?? 0);
                    $prix = (float)($a['prix_unitaire'] ?? 0);
                    $montant = (float)($a['montant_total'] ?? 0);
                    $unite = $a['besoin_unite'] ?? '';
                    $villeId = $a['ville_id'] ?? null;

                    $url = '/achat?' . http_build_query(['ville_id' => $villeId]);
                  ?>
                  <tr data-ville="<?= (int)$villeId ?>">
                    <td><?= htmlspecialchars($a['date_achat'] ?? '') ?></td>

                    <td class="fw-semibold">
                      <?= htmlspecialchars($a['ville_nom'] ?? '') ?>
                    </td>

                    <td>
                      <span class="badge bg-light text-dark">
                        <?= htmlspecialchars($a['type_nom'] ?? '') ?>
                      </span>
                    </td>

                    <td>
                      <?= htmlspecialchars($a['besoin_description'] ?? '') ?>
                    </td>

                    <td class="text-end fw-bold">
                      <?= number_format($quantite, 0, ',', ' ') ?>
                      <?= htmlspecialchars($unite) ?>
                    </td>

                    <td class="text-end">
                      <?= number_format($prix, 0, ',', ' ') ?> Ar
                    </td>

                    <td class="text-end fw-bold text-primary">
                      <?= number_format($montant, 0, ',', ' ') ?> Ar
                    </td>

                    <td class="text-center">
                      <a href="<?= $url ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi-eye"></i> Voir ville
                      </a>
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

<script nonce="<?= formatText($cspNonce) ?>">
document.addEventListener('DOMContentLoaded', () => {

  const filter = document.getElementById('villeFilter');
  const resetBtn = document.getElementById('resetFilter');
  const rows = document.querySelectorAll('tbody tr');

  function applyFilter() {
    const selected = filter.value;

    rows.forEach(row => {
      const villeId = row.dataset.ville;

      if (!selected || villeId === selected) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  filter.addEventListener('change', applyFilter);

  resetBtn.addEventListener('click', () => {
    filter.value = '';
    applyFilter();
  });

});
</script>

