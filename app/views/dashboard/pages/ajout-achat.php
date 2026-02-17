<?php
$villes = $villes ?? [];
$besoins = $besoins ?? [];
$argent_disponible = $argent_disponible ?? 0;
$errors = $errors ?? [];
$values = $values ?? [];
?>

<section class="section-padding" data-argent-disponible="<?= (float)$argent_disponible ?>">
  <div class="container">

    <div class="text-center mb-4">
      <h2>Faire un achat</h2>
      <p class="text-muted">Utiliser les dons en argent.</p>

      <div class="custom-block p-3 d-inline-block">
        <small>Argent disponible</small>
        <h4><?= number_format($argent_disponible, 0, ',', ' ') ?> Ar</h4>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 mx-auto">

        <div class="custom-block p-4">

          <form action="/achat/ajout-achat" method="POST">

            <!-- Ville -->
            <div class="mb-3">
              <label>Ville</label>
              <select id="villeSelect" name="ville_id" class="form-select">
                <option value="">Choisir...</option>
                <?php foreach ($villes as $v): ?>
                  <option value="<?= $v['id'] ?>">
                    <?= htmlspecialchars($v['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Besoin -->
            <div class="mb-3">
              <label>Besoin</label>
              <select id="besoinSelect" name="besoin_id" class="form-select">
                <option value="">Choisir...</option>
                <?php foreach ($besoins as $b):
                  if ($b['type_nom'] === 'Nature' || $b['type_nom'] === 'Matériaux'): ?>
                  <option value="<?= (int)$b['id'] ?>"
                          data-ville="<?= (int)$b['ville_id'] ?>"
                          data-prix="<?= (float)$b['prix_unitaire'] ?>"
                          data-unite="<?= htmlspecialchars($b['unite']) ?>">
                    <?= htmlspecialchars($b['description']) ?> (<?= htmlspecialchars($b['ville_nom']) ?>)
                  </option>
                <?php endif; endforeach; ?>
              </select>
            </div>
            
            <div id="montantError" class="alert alert-danger d-none mt-3">
            </div>

            <!-- Quantité -->
            <div class="mb-3">
              <label>Quantité</label>
              <input type="number" step="0.01" name="quantite" id="quantiteInput" class="form-control">
              <small id="uniteText" class="text-muted"></small>
            </div>

            <!-- Affichage dynamique -->
            <div class="row mb-3">
              <div class="col-md-6">
                <strong>Prix unitaire :</strong>
                <span id="prixText">—</span>
              </div>
              <div class="col-md-6">
                <strong>Montant :</strong>
                <span id="montantText">—</span>
              </div>
            </div>

            <div class="mb-3">
              <label>Remarque</label>
              <textarea name="remarque" class="form-control"></textarea>
            </div>

            <button class="custom-btn btn">Valider</button>

          </form>

        </div>

      </div>
    </div>

  </div>
</section>

<script src="/assets/js/achat.js" nonce="<?= formatText($cspNonce) ?>"></script>

