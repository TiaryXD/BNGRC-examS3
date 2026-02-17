document.addEventListener("DOMContentLoaded", () => {

    const section = document.querySelector(".section-padding");

    const argentDisponible =
        parseFloat(section.dataset.argentDisponible || 0);

    const villeSelect   = document.getElementById("villeSelect");
    const besoinSelect  = document.getElementById("besoinSelect");
    const quantiteInput = document.getElementById("quantiteInput");

    const prixText    = document.getElementById("prixText");
    const montantText = document.getElementById("montantText");
    const uniteText   = document.getElementById("uniteText");

    const errorBox = document.getElementById("montantError");
    const submitBtn = document.querySelector("button[type='submit']");

    function formatAr(n) {
        return Math.round(n).toLocaleString("fr-FR") + " Ar";
    }

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.classList.remove("d-none");
        montantText.style.color = "red";
        submitBtn.disabled = true;
    }

    function hideError() {
        errorBox.classList.add("d-none");
        montantText.style.color = "";
        submitBtn.disabled = false;
    }

    function updateMontant() {

        const opt = besoinSelect.options[besoinSelect.selectedIndex];

        if (!opt || !opt.value) {
            prixText.textContent = "—";
            montantText.textContent = "—";
            uniteText.textContent = "";
            hideError();
            return;
        }

        const prix = parseFloat(opt.dataset.prix || 0);
        const unite = opt.dataset.unite || "";
        const qte = parseFloat(quantiteInput.value || 0);

        const montant = prix * qte;

        prixText.textContent = formatAr(prix);
        montantText.textContent = formatAr(montant);
        uniteText.textContent = "Unité : " + unite;

        // ✅ validation argent disponible
        if (montant > argentDisponible) {
            showError(
                `Montant (${formatAr(montant)}) supérieur à l'argent disponible (${formatAr(argentDisponible)})`
            );
        } else {
            hideError();
        }
    }

    function filterByVille() {
        const villeId = villeSelect.value;

        Array.from(besoinSelect.options).forEach(opt => {
            if (!opt.value) return;

            const optVille = String(opt.dataset.ville);
            opt.hidden = villeId && optVille !== villeId;
        });

        besoinSelect.value = "";
        updateMontant();
    }

    villeSelect.addEventListener("change", filterByVille);
    besoinSelect.addEventListener("change", updateMontant);
    quantiteInput.addEventListener("input", updateMontant);

});
