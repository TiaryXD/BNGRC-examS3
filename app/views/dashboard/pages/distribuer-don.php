<?php
$don_nom = $don_nom ?? '';
$unite = $unite ?? '';
$reste_stock = (float)($reste_stock ?? 0);

$villes = $villes ?? [];
$besoins = $besoins ?? [];
$errors = $errors ?? [];
$values = $values ?? [];

function invalid($k, $errors){ return !empty($errors[$k]) ? 'is-invalid' : ''; }

$selectedVille = $values['ville_id'] ?? '';
$selectedBesoin = $values['besoin_id'] ?? '';
?>
<?php
if (empty($_SESSION['user'])): ?>
  <div class="alert alert-warning">
    Accès réservé aux administrateurs.
    <a href="/login" class="alert-link">Se connecter</a>
  </div>
  <?php return; ?>
<?php endif; ?>

<section class="section-padding">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-12">

        <div class="custom-block p-4 p-lg-5">

          <div class="text-center mb-4">
            <h2 class="mb-2">Distribuer un don</h2>
            <p class="text-muted mb-0">Stock restant : <strong><?= number_format($reste_stock, 0, ',', ' ') ?> <?= htmlspecialchars($unite) ?></strong></p>
          </div>

          <?php if(!empty($errors['besoin_id'])): ?>
            <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['besoin_id']) ?></div>
            <?php endif; ?>

          <form method="POST" action="/distribuer/save" class="row g-3" novalidate>

            <input type="hidden" name="don_nom" value="<?= htmlspecialchars($don_nom) ?>">
            <input type="hidden" name="unite" value="<?= htmlspecialchars($unite) ?>">

            <div class="col-md-6">
              <label class="form-label fw-semibold">Don</label>
              <input class="form-control" value="<?= htmlspecialchars($don_nom) ?>" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Unité</label>
              <input class="form-control" value="<?= htmlspecialchars($unite) ?>" readonly>
            </div>

            <!-- Ville -->
            <div class="col-12">
              <label class="form-label fw-semibold">Ville</label>
              <select name="ville_id" class="form-select <?= invalid('ville_id',$errors) ?>"
                      onchange="window.location='<?= '/distribuer?don='.urlencode($don_nom).'&unite='.urlencode($unite) ?>&ville_id='+this.value"
                      required>
                <option value="">-- Choisir une ville --</option>
                <?php foreach($villes as $v): ?>
                  <option value="<?= (int)$v['id'] ?>" <?= ((string)$selectedVille === (string)$v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if(!empty($errors['ville_id'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['ville_id']) ?></div>
              <?php endif; ?>
            </div>

            <!-- Quantité -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Quantité à distribuer</label>
              <input type="number"
                     step="0.01"
                     min="0"
                     max="<?= htmlspecialchars((string)$reste_stock) ?>"
                     name="quantite"
                     class="form-control <?= invalid('quantite',$errors) ?>"
                     value="<?= htmlspecialchars($values['quantite'] ?? '') ?>"
                     required>
              <?php if(!empty($errors['quantite'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['quantite']) ?></div>
              <?php endif; ?>
              <small class="text-muted">Max: <?= number_format($reste_stock, 0, ',', ' ') ?> <?= htmlspecialchars($unite) ?></small>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Remarque</label>
              <input name="remarque" class="form-control" value="<?= htmlspecialchars($values['remarque'] ?? '') ?>">
            </div>

            <div class="col-12 d-flex justify-content-between mt-3">
              <a href="/stat-don" class="btn btn-outline-secondary"><i class="bi-arrow-left"></i> Retour</a>
              <button class="custom-btn btn" type="submit">
                Valider <i class="bi-check2-circle ms-1"></i>
              </button>
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>
</section>
