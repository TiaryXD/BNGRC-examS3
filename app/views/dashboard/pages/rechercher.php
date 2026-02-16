<header class="mb-0">
    <h2 class="fw-bold title">RECHERCHER<span style="color: var(--lime-color)">.</span></h2>
    <p class="text-muted small">Trouvez ce que vous cherchez dans notre catalogue.</p>
</header>

<div class="search-section">
    <form action="/rechercher" method="GET" class="row g-4 align-items-end">
        <div class="col-md-5">
            <label for="keyword" class="form-label text-white small fw-bold">MOT-CLÉ</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Que cherchez-vous ?">
            </div>
        </div>

        <div class="col-md-4">
            <label for="category" class="form-label text-white small fw-bold">CATÉGORIE</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                <select name="category" id="category" class="form-select">
                    <option value="all" selected>Tous</option>
                    <option value="electronics">Électronique</option>
                    <option value="fashion">Mode</option>
                    <option value="home">Maison</option>
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <button type="submit" class="btn btn-lime w-100 py-2">
                RECHERCHER
            </button>
        </div>
    </form>
</div>
<hr class="my-4 border-secondary">

