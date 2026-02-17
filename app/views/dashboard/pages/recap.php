<?php
$recap = $recap ?? [
  'besoins_total_montant' => 0,
  'besoins_satisfaits_montant' => 0,
  'dons_recus_montant' => 0,
  'dons_dispatches_montant' => 0,
];

function ar($n) {
  return number_format((float)$n, 0, ',', ' ') . ' Ar';
}

$bt = (float)$recap['besoins_total_montant'];
$bs = (float)$recap['besoins_satisfaits_montant'];
$dr = (float)$recap['dons_recus_montant'];
$dd = (float)$recap['dons_dispatches_montant'];

$pourBesoins = ($bt > 0) ? min(100, ($bs / $bt) * 100) : 0;
$pourDons    = ($dr > 0) ? min(100, ($dd / $dr) * 100) : 0;
?>

<section class="section-padding">
  <div class="container">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <div>
        <h2 class="mb-1">Récapitulatif</h2>
        <p class="text-muted mb-0">Besoins & Dons (montants estimés)</p>
      </div>

      <button id="btnRefresh" class="custom-btn btn">
        <i class="bi-arrow-clockwise me-1"></i> Actualiser
      </button>
    </div>

    <div id="recapAlert" class="alert d-none"></div>

    <div class="row g-4">
      <!-- Besoins -->
      <div class="col-lg-6">
        <div class="custom-block p-4 h-100">
          <h5 class="mb-3"><i class="bi-clipboard-data me-1"></i> Besoins</h5>

          <div class="d-flex justify-content-between small text-muted">
            <span>Total</span>
            <strong id="besoinsTotal"><?= ar($bt) ?></strong>
          </div>

          <div class="d-flex justify-content-between small text-muted mt-2">
            <span>Satisfaits</span>
            <strong id="besoinsSatisfaits"><?= ar($bs) ?></strong>
          </div>

          <div class="progress mt-3" style="height:10px;">
            <div id="besoinsBar" class="progress-bar" role="progressbar" style="width: <?= (float)$pourBesoins ?>%"></div>
          </div>
          <div class="small text-muted mt-2">
            Couverture : <span id="besoinsPct"><?= number_format($pourBesoins, 1, ',', ' ') ?>%</span>
          </div>
        </div>
      </div>

      <!-- Dons -->
      <div class="col-lg-6">
        <div class="custom-block p-4 h-100">
          <h5 class="mb-3"><i class="bi-box2-heart me-1"></i> Dons</h5>

          <div class="d-flex justify-content-between small text-muted">
            <span>Reçus</span>
            <strong id="donsRecus"><?= ar($dr) ?></strong>
          </div>

          <div class="d-flex justify-content-between small text-muted mt-2">
            <span>Dispatchés</span>
            <strong id="donsDispatches"><?= ar($dd) ?></strong>
          </div>

          <div class="progress mt-3" style="height:10px;">
            <div id="donsBar" class="progress-bar" role="progressbar" style="width: <?= (float)$pourDons ?>%"></div>
          </div>
          <div class="small text-muted mt-2">
            Utilisation : <span id="donsPct"><?= number_format($pourDons, 1, ',', ' ') ?>%</span>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<script nonce="<?= formatText($cspNonce) ?>">
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnRefresh');
  const alertBox = document.getElementById('recapAlert');

  const el = {
    bt: document.getElementById('besoinsTotal'),
    bs: document.getElementById('besoinsSatisfaits'),
    bp: document.getElementById('besoinsPct'),
    bb: document.getElementById('besoinsBar'),

    dr: document.getElementById('donsRecus'),
    dd: document.getElementById('donsDispatches'),
    dp: document.getElementById('donsPct'),
    db: document.getElementById('donsBar'),
  };

  function ar(n){
    return Math.round(n).toLocaleString('fr-FR') + ' Ar';
  }

  function showAlert(type, msg){
    alertBox.className = 'alert alert-' + type;
    alertBox.textContent = msg;
    alertBox.classList.remove('d-none');
    setTimeout(() => alertBox.classList.add('d-none'), 2500);
  }

  btn.addEventListener('click', async () => {
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualisation...';

    try {
      const res = await fetch('/api/recap', { headers: { 'Accept': 'application/json' } });
      const json = await res.json();
      if (!json.ok) throw new Error('Réponse invalide');

      const d = json.data;

      const bt = Number(d.besoins_total_montant || 0);
      const bs = Number(d.besoins_satisfaits_montant || 0);
      const dr = Number(d.dons_recus_montant || 0);
      const dd = Number(d.dons_dispatches_montant || 0);

      const pctB = bt > 0 ? Math.min(100, (bs / bt) * 100) : 0;
      const pctD = dr > 0 ? Math.min(100, (dd / dr) * 100) : 0;

      el.bt.textContent = ar(bt);
      el.bs.textContent = ar(bs);
      el.bp.textContent = pctB.toFixed(1).replace('.', ',') + '%';
      el.bb.style.width = pctB + '%';

      el.dr.textContent = ar(dr);
      el.dd.textContent = ar(dd);
      el.dp.textContent = pctD.toFixed(1).replace('.', ',') + '%';
      el.db.style.width = pctD + '%';

      showAlert('success', 'Récap mis à jour.');
    } catch (e) {
      console.error(e);
      showAlert('danger', 'Erreur lors de l’actualisation.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi-arrow-clockwise me-1"></i> Actualiser';
    }
  });
});
</script>
