<?php
$dons = $dons ?? [];
$reduction_pct = (float)($reduction_pct ?? 0);
?>
<section class="section-padding">
  <div class="container">
    <?php if (!empty($errors['global'])): ?>
    <div class="alert alert-danger">
        <i class="bi-exclamation-triangle me-2"></i>
        <?= htmlspecialchars($errors['global']) ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <i class="bi-check-circle me-2"></i>
        Vente enregistrée avec succès.
    </div>
    <?php endif; ?>


    <div class="text-center mb-4">
      <h2>Vente des dons</h2>
      <p class="text-muted mb-0">
        Les dons liés à des besoins existants ne peuvent pas être vendus.
      </p>
      <div class="custom-block p-3 d-inline-block mt-3">
        <small>Réduction appliquée</small>
        <h4 class="mb-0"><?= number_format($reduction_pct, 0) ?>%</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-10 mx-auto">
        <div class="custom-block p-4">

          <?php if (empty($dons)): ?>
            <p class="text-muted mb-0">Aucun don vendable pour le moment.</p>
          <?php else: ?>

          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Type</th>
                  <th>Don</th>
                  <th class="text-end">Stock</th>
                  <th class="text-end">Prix unitaire</th>
                  <th class="text-end">Prix (-<?= number_format($reduction_pct,0) ?>%)</th>
                  <th class="text-center">Vendre</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($dons as $d):
                $stock = (float)$d['quantite'];
                $pu = (float)($d['prix_unitaire'] ?? 0);
                $puFinal = $pu * (1 - ($reduction_pct/100));
              ?>
                <tr>
                  <td><span class="badge bg-light text-dark"><?= htmlspecialchars($d['type_nom']) ?></span></td>
                  <td class="fw-semibold"><?= htmlspecialchars($d['description']) ?></td>
                  <td class="text-end"><?= number_format($stock, 0, ',', ' ') ?> <?= htmlspecialchars($d['unite']) ?></td>
                  <td class="text-end"><?= number_format($pu, 0, ',', ' ') ?> Ar</td>
                  <td class="text-end text-primary fw-bold"><?= number_format($puFinal, 0, ',', ' ') ?> Ar</td>

                  <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#sellModal"
                            data-id="<?= (int)$d['id'] ?>"
                            data-desc="<?= htmlspecialchars($d['description'], ENT_QUOTES) ?>"
                            data-stock="<?= $stock ?>"
                            data-unite="<?= htmlspecialchars($d['unite'], ENT_QUOTES) ?>"
                            data-pu="<?= $pu ?>"
                            data-reduc="<?= $reduction_pct ?>">
                      Vendre
                    </button>
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

<div class="modal fade" id="sellModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="/vente" id="sellForm">
      <div class="modal-header">
        <h5 class="modal-title">Vendre un don</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="don_id" id="donId">

        <div class="mb-2">
          <div class="fw-bold" id="donLabel">—</div>
          <small class="text-muted" id="donStock">—</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Quantité à vendre</label>
          <input type="number" step="0.01" class="form-control" name="quantite" id="qte" required>
          <div class="invalid-feedback" id="qteErr">Quantité invalide.</div>
        </div>

        <div class="d-flex justify-content-between">
          <span>Prix (-réduction)</span>
          <span class="fw-bold text-primary" id="puFinalText">—</span>
        </div>
        <div class="d-flex justify-content-between">
          <span>Montant total</span>
          <span class="fw-bold" id="totalText">—</span>
        </div>

        <div class="mt-3">
          <label class="form-label">Remarque</label>
          <textarea class="form-control" name="remarque"></textarea>
        </div>

        <div class="alert alert-danger d-none mt-3" id="sellError"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Annuler</button>
        <button class="custom-btn btn" type="submit" id="sellBtn">Valider</button>
      </div>
    </form>
  </div>
</div>

<script src="/assets/js/vente.js" nonce="<?= formatText($cspNonce) ?>"></script>

