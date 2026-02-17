<?php
$villes = $villes ?? [];
$besoins = $besoins ?? [];
$argent_disponible = $argent_disponible ?? 0;
$errors = $errors ?? [];
$values = $values ?? [];
?>

<section class="section-padding">
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
                  <option value="<?= $b['id'] ?>"
                          data-ville="<?= $b['ville_id'] ?>"
                          data-prix="<?= $b['prix_unitaire'] ?>"
                          data-unite="<?= $b['unite'] ?>">
                    <?= htmlspecialchars($b['description']) ?>
                    (<?= htmlspecialchars($b['ville_nom']) ?>)
                  </option>
                <?php endif; endforeach; ?>
              </select>
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

<script>
const villeSelect = document.getElementById('villeSelect');
const besoinSelect = document.getElementById('besoinSelect');
const quantiteInput = document.getElementById('quantiteInput');
const prixText = document.getElementById('prixText');
const montantText = document.getElementById('montantText');
const uniteText = document.getElementById('uniteText');

function formatAr(n) {
  return Math.round(n).toLocaleString('fr-FR') + ' Ar';
}

function updateMontant() {
  const opt = besoinSelect.options[besoinSelect.selectedIndex];
  if (!opt || !opt.value) {
    prixText.textContent = '—';
    montantText.textContent = '—';
    uniteText.textContent = '';
    return;
  }

  const prix = parseFloat(opt.dataset.prix) || 0;
  const unite = opt.dataset.unite || '';
  const qte = parseFloat(quantiteInput.value) || 0;

  prixText.textContent = formatAr(prix);
  montantText.textContent = formatAr(prix * qte);
  uniteText.textContent = "Unité : " + unite;
}

function filterByVille() {
  const villeId = villeSelect.value;

  for (let opt of besoinSelect.options) {
    if (!opt.value) continue;
    opt.hidden = (villeId && opt.dataset.ville !== villeId);
  }

  besoinSelect.value = "";
  updateMontant();
}

villeSelect.addEventListener('change', filterByVille);
besoinSelect.addEventListener('change', updateMontant);
quantiteInput.addEventListener('input', updateMontant);
</script>
