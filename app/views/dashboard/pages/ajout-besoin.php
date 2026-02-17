<?php
$errors = $errors ?? [];
$values = $values ?? [];

$villes = $villes ?? [];
$types  = $types ?? [];

$selectedVille = $values['ville_id'] ?? ($_GET['ville_id'] ?? null);

function fieldInvalid($name, $errors) {
    return !empty($errors[$name]) ? 'is-invalid' : '';
}
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
            <h2 class="mb-2">Ajouter un besoin</h2>
            <p class="text-muted mb-0">Renseignez les informations du besoin pour une ville sinistrée.</p>
          </div>

          <form method="POST" action="/save-besoin" class="row g-3" novalidate>

            <!-- VILLE -->
            <div class="col-12">
              <label class="form-label fw-semibold">Ville</label>
              <select name="ville_id" class="form-select <?= fieldInvalid('ville_id', $errors) ?>" required>
                <option value="">-- Choisir une ville --</option>
                <?php foreach ($villes as $v): ?>
                  <option value="<?= (int)$v['id'] ?>"
                    <?= ((string)$selectedVille === (string)$v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['ville_id'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['ville_id']) ?></div>
              <?php endif; ?>
            </div>

            <!-- TYPE -->
            <div class="col-12">
              <label class="form-label fw-semibold">Type</label>
              <select name="type_id" class="form-select <?= fieldInvalid('type_id', $errors) ?>" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($types as $t): ?>
                  <option value="<?= (int)$t['id'] ?>"
                    <?= (!empty($values['type_id']) && (string)$values['type_id'] === (string)$t['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (!empty($errors['type_id'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['type_id']) ?></div>
              <?php endif; ?>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-12">
              <label class="form-label fw-semibold">Description</label>
              <input type="text"
                     name="description"
                     class="form-control <?= fieldInvalid('description', $errors) ?>"
                     placeholder="Ex: Riz blanc, Eau potable, Tôle ondulée..."
                     value="<?= htmlspecialchars($values['description'] ?? '') ?>"
                     required>
              <?php if (!empty($errors['description'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['description']) ?></div>
              <?php endif; ?>
            </div>

            <!-- QUANTITE + UNITE -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Quantité</label>
              <input type="number"
                     step="0.01"
                     min="0"
                     name="quantite"
                     class="form-control <?= fieldInvalid('quantite', $errors) ?>"
                     placeholder="Ex: 1000"
                     value="<?= htmlspecialchars($values['quantite'] ?? '') ?>"
                     required>
              <?php if (!empty($errors['quantite'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['quantite']) ?></div>
              <?php endif; ?>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Unité</label>
              <input type="text"
                     name="unite"
                     class="form-control <?= fieldInvalid('unite', $errors) ?>"
                     placeholder="Ex: kg, litre, pièce, Ar..."
                     value="<?= htmlspecialchars($values['unite'] ?? '') ?>"
                     required>
              <?php if (!empty($errors['unite'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['unite']) ?></div>
              <?php endif; ?>
            </div>

            <!-- REMARQUE -->
            <div class="col-12">
              <label class="form-label fw-semibold">Remarque (optionnel)</label>
              <textarea name="remarque" class="form-control" rows="3"
                        placeholder="Infos complémentaires..."><?= htmlspecialchars($values['remarque'] ?? '') ?></textarea>
            </div>

            <!-- ACTIONS -->
            <div class="col-12 d-flex flex-wrap gap-2 justify-content-between align-items-center mt-2">
              <a href="<?= $selectedVille ? '/ville/' . (int)$selectedVille : '/ville' ?>"
                 class="btn btn-outline-secondary">
                <i class="bi-arrow-left"></i> Annuler
              </a>

              <button type="submit" class="custom-btn btn">
                Enregistrer
                <i class="bi-check2-circle ms-1"></i>
              </button>
            </div>

          </form>

        </div>

      </div>
    </div>

  </div>
</section>
