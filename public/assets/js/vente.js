document.addEventListener('DOMContentLoaded', () => {

  const modal = document.getElementById('sellModal');
  if (!modal) return; 

  const donId = document.getElementById('donId');
  const donLabel = document.getElementById('donLabel');
  const donStock = document.getElementById('donStock');
  const qte = document.getElementById('qte');
  const puFinalText = document.getElementById('puFinalText');
  const totalText = document.getElementById('totalText');
  const sellError = document.getElementById('sellError');
  const sellBtn = document.getElementById('sellBtn');

  let stock = 0;
  let unite = '';
  let pu = 0;
  let reduc = 0;

  function ar(n) {
    return Math.round(n).toLocaleString('fr-FR') + ' Ar';
  }

  function refresh() {
    const q = parseFloat(qte.value || 0);

    const puFinal = pu * (1 - (reduc / 100));
    const total = puFinal * q;

    puFinalText.textContent = ar(puFinal);
    totalText.textContent = q > 0 ? ar(total) : '—';

    sellError.classList.add('d-none');
    sellBtn.disabled = false;

    if (q <= 0) return;

    if (q > stock) {
      sellError.textContent = "Quantité supérieure au stock disponible.";
      sellError.classList.remove('d-none');
      sellBtn.disabled = true;
    }
  }

  modal.addEventListener('show.bs.modal', (ev) => {

    const btn = ev.relatedTarget;

    const id = btn.dataset.id;
    const desc = btn.dataset.desc;

    stock = parseFloat(btn.dataset.stock || 0);
    unite = btn.dataset.unite || '';
    pu = parseFloat(btn.dataset.pu || 0);
    reduc = parseFloat(btn.dataset.reduc || 0);

    donId.value = id;
    donLabel.textContent = desc;
    donStock.textContent = `Stock : ${stock} ${unite}`;

    qte.value = "";
    refresh();
  });

  qte.addEventListener('input', refresh);

});
