<?php
$errors = $errors ?? [];
$values = $values ?? [];

$types = $types ?? []; // attendu depuis le controller

function fieldInvalid($name, $errors) {
  return !empty($errors[$name]) ? 'is-invalid' : '';
}
?>

<section class="section-padding">
  <div class="container">

    <div class="row justify-content-center">
      <div class="col-lg-8 col-12">

        <div class="custom-block p-4 p-lg-5">

          <div class="text-center mb-4">
            <h2 class="mb-2">Entrer un don</h2>
            <p class="text-muted mb-0">Ajoutez un nouveau don reçu dans l’inventaire.</p>
          </div>

          <form method="POST" action="/dons/save" class="row g-3" novalidate>

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
                     placeholder="Ex: Riz blanc, Eau potable, Couvertures..."
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
                     name="quantite"
                     step="0.01"
                     min="0"
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
                     placeholder="Ex: kg, litre, pièce..."
                     value="<?= htmlspecialchars($values['unite'] ?? '') ?>"
                     required>
              <?php if (!empty($errors['unite'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['unite']) ?></div>
              <?php endif; ?>
            </div>

            <!-- DATE RECEPTION -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Date de réception</label>
              <input type="date"
                     name="date_reception"
                     class="form-control <?= fieldInvalid('date_reception', $errors) ?>"
                     value="<?= htmlspecialchars($values['date_reception'] ?? '') ?>"
                     required>
              <?php if (!empty($errors['date_reception'])): ?>
                <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['date_reception']) ?></div>
              <?php endif; ?>
            </div>

            <!-- SOURCE -->
            <div class="col-md-6">
              <label class="form-label fw-semibold">Source (optionnel)</label>
              <input type="text"
                     name="source"
                     class="form-control"
                     placeholder="Ex: ONG, Entreprise, Particulier..."
                     value="<?= htmlspecialchars($values['source'] ?? '') ?>">
            </div>

            <!-- REMARQUE -->
            <div class="col-12">
              <label class="form-label fw-semibold">Remarque (optionnel)</label>
              <textarea name="remarque" class="form-control" rows="3"
                        placeholder="Informations complémentaires..."><?= htmlspecialchars($values['remarque'] ?? '') ?></textarea>
            </div>

            <!-- ACTIONS -->
            <div class="col-12 d-flex justify-content-between align-items-center mt-2">
              <a href="/dons" class="btn btn-outline-secondary">
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
