document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnRefresh');
  const resetLink = document.getElementById('resetLink');
  const alertBox = document.getElementById('recapAlert');

  // Si la page recap n'a pas ces éléments, on sort (évite erreurs si script chargé ailleurs)
  if (!btn || !alertBox) return;

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

  function ar(n) {
    return Math.round(n).toLocaleString('fr-FR') + ' Ar';
  }

  function showAlert(type, msg) {
    alertBox.className = 'alert alert-' + type;
    alertBox.textContent = msg;
    alertBox.classList.remove('d-none');
    setTimeout(() => alertBox.classList.add('d-none'), 2500);
  }

  async function refreshRecap() {
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Actualisation...';

    try {
      const res = await fetch('/api/recap', { headers: { 'Accept': 'application/json' } });
      const json = await res.json();
      if (!json.ok) throw new Error(json.error || 'Réponse invalide');

      const d = json.data || {};

      const bt = Number(d.besoins_total_montant || 0);
      const bs = Number(d.besoins_satisfaits_montant || 0);
      const dr = Number(d.dons_recus_montant || 0);
      const dd = Number(d.dons_dispatches_montant || 0);

      const pctB = bt > 0 ? Math.min(100, (bs / bt) * 100) : 0;
      const pctD = dr > 0 ? Math.min(100, (dd / dr) * 100) : 0;

      if (el.bt) el.bt.textContent = ar(bt);
      if (el.bs) el.bs.textContent = ar(bs);
      if (el.bp) el.bp.textContent = pctB.toFixed(1).replace('.', ',') + '%';
      if (el.bb) el.bb.style.width = pctB + '%';

      if (el.dr) el.dr.textContent = ar(dr);
      if (el.dd) el.dd.textContent = ar(dd);
      if (el.dp) el.dp.textContent = pctD.toFixed(1).replace('.', ',') + '%';
      if (el.db) el.db.style.width = pctD + '%';

      showAlert('success', 'Récap mis à jour.');
    } catch (e) {
      console.error(e);
      showAlert('danger', 'Erreur lors de l’actualisation.');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi-arrow-clockwise me-1"></i> Actualiser';
    }
  }

  // ✅ Bouton Actualiser
  btn.addEventListener('click', (e) => {
    e.preventDefault();
    refreshRecap();
  });

  // ✅ Lien Réinitialiser (optionnel)
  if (resetLink) {
    resetLink.addEventListener('click', async (e) => {
      e.preventDefault();

      const ok = confirm(
        "Réinitialiser ?\n\n" +
        "• Toutes les données NON base seront supprimées\n" +
        "• Distributions et achats seront vidés\n"
      );
      if (!ok) return;

      resetLink.classList.add('disabled');
      const oldHtml = resetLink.innerHTML;
      resetLink.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Réinitialisation...';

      try {
        const res = await fetch('/api/reset-data', {
          method: 'POST',
          headers: { 'Accept': 'application/json' }
        });

        const json = await res.json();
        if (!json.ok) throw new Error(json.error || 'Erreur reset');

        showAlert('success', 'Réinitialisation terminée.');
        // On recharge les valeurs du recap sans recharger la page
        await refreshRecap();
      } catch (err) {
        console.error(err);
        showAlert('danger', 'Reset impossible : ' + err.message);
      } finally {
        resetLink.classList.remove('disabled');
        resetLink.innerHTML = oldHtml;
      }
    });
  }
});
