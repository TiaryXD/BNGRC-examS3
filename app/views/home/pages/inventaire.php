<header class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 gap-3">
    <div>
        <h2 class="fw-bold title uppercase-text">INVENTAIRE<span class="text-lime">.</span></h2>
        <p class="text-muted small mb-0">Gestion de vos objets</p>
    </div>
    <button class="btn btn-lime px-4 py-2" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus-lg me-2"></i>AJOUTER UN OBJET
    </button>
</header>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark-card border-0 rounded-0">
            <div class="modal-header border-secondary border-opacity-25 p-4">
                <h5 class="modal-title fw-bold">NOUVEL ITEM<span class="text-lime">.</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="inventoryForm" class="row g-4">
                    <div class="col-12">
                        <label class="form-label text-white-50 small fw-bold">NOM DE L'OBJET</label>
                        <input type="text" class="form-control" placeholder="Désignation de l'objet">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small fw-bold">CATÉGORIE</label>
                        <select class="form-select">
                            <option selected>Choisir...</option>
                            <option>Électronique</option>
                            <option>Mobilier</option>
                            <option>Divers</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white-50 small fw-bold">QUANTITÉ</label>
                        <input type="number" class="form-control" value="1">
                    </div>
                    <div class="col-12 pt-3">
                        <button type="submit" class="btn btn-lime w-100 py-3">CONFIRMER L'AJOUT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>