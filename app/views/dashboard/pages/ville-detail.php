<?php
$ville   = $ville ?? null;
$besoin = $besoin ?? [];
$distribution = $distribution ?? [];

if (!$ville) {
    echo "<p class='text-danger'>Ville introuvable.</p>";
    return;
}

$totalDemande = count($besoin);
?>

<section class="section-padding">
    <div class="container">

        <div class="row align-items-center mb-4">

            <div class="col-lg-8">
                <h2 class="mb-2">
                    <i class="bi-geo-alt-fill text-warning"></i>
                    <?= htmlspecialchars($ville['nom']) ?>
                </h2>

                <p class="text-muted mb-0">
                    Région :
                    <strong><?= htmlspecialchars($ville['region_nom'] ?? '—') ?></strong>

                    <?php if (!empty($ville['population'])): ?>
                        · Population :
                        <strong><?= number_format($ville['population'],0,',',' ') ?></strong>
                    <?php endif; ?>
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="/ville" class="btn btn-outline-secondary">
                    <i class="bi-arrow-left"></i> Retour
                </a>
            </div>

        </div>


        <div class="row mb-4 justify-content-center">
            <div class="col-md-4">
                <div class="custom-block p-3 text-center">
                    <small class="text-muted">Total besoins</small>
                    <h4><?= number_format($totalDemande,0,',',' ') ?></h4>
                </div>
            </div>
        </div>


        <div class="custom-block p-4">

        <h5 class="mb-3">Liste des besoins</h5>

        <?php if (empty($besoin)): ?>

            <p class="text-muted">Aucun besoin enregistré pour cette ville.</p>

        <?php else: ?>

        <div class="table-responsive">
        <table class="table table-bordered align-middle">

        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Description</th>
            <th class="text-end">Quantité</th>
            <th>Unité</th>
            <th>Remarque</th>
            <th>Date</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($besoin as $b): ?>
        <tr>
            <td><?= $b['id'] ?></td>

            <td><?= htmlspecialchars($b['type_nom'] ?? '') ?></td>

            <td><?= htmlspecialchars($b['description']) ?></td>

            <td class="text-end fw-bold">
                <?= number_format($b['quantite'],0,',',' ') ?>
            </td>

            <td><?= htmlspecialchars($b['unite']) ?></td>

            <td class="small text-muted">
                <?= htmlspecialchars($b['remarque'] ?? '') ?>
            </td>

            <td class="small text-muted">
                <?= htmlspecialchars($b['created_at']) ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>

        </table>
        </div>

        <?php endif; ?>

        </div>

    </div>

        <div class="custom-block p-4 mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Dons reçus (Distributions)</h5>
    </div>

    <?php if (empty($distribution)): ?>
        <p class="text-muted mb-0">Aucune distribution enregistrée pour cette ville.</p>
    <?php else: ?>

        <!-- CARDS -->
        <div class="row">
        <?php foreach ($distribution as $di): ?>
            <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="custom-block-wrap">

                <div class="custom-block">
                <div class="custom-block-body">

                    <small class="text-muted">
                    <i class="bi-tag me-1"></i>
                    <?= htmlspecialchars($di['type_nom'] ?? '') ?>
                    </small>

                    <h5 class="mt-2 mb-2">
                    <?= htmlspecialchars($di['don_description'] ?? '') ?>
                    </h5>

                    <ul class="list-unstyled small mb-2">
                    <li>
                        <i class="bi-box-seam text-warning"></i>
                        <strong>Quantité :</strong>
                        <?= number_format((float)($di['distribution_quantite'] ?? 0), 0, ',', ' ') ?>
                        <?= htmlspecialchars($di['don_unite'] ?? '') ?>
                    </li>

                    <li>
                        <i class="bi-clipboard-check text-success"></i>
                        <strong>Besoin :</strong>
                        <?= htmlspecialchars($di['besoin_description'] ?? '') ?>
                        (<?= htmlspecialchars($di['besoin_unite'] ?? '') ?>)
                    </li>

                    <li>
                        <i class="bi-calendar-event text-primary"></i>
                        <strong>Date :</strong>
                        <?= htmlspecialchars($di['distribution_date'] ?? '') ?>
                    </li>
                    </ul>

                    <?php if (!empty($di['distribution_remarque'])): ?>
                    <p class="text-muted small mb-0">
                        <?= htmlspecialchars($di['distribution_remarque']) ?>
                    </p>
                    <?php endif; ?>

                </div>
                </div>
            </div>
            </div>
        <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <div class="row mt-5">
        <div class="col-12 text-center">

            <a href="/besoin/create?ville_id=<?= (int)$ville['id'] ?>"
            class="custom-btn btn btn-warning px-4 py-2">

                <i class="bi-plus-circle me-2"></i>
                Ajouter un besoin pour cette ville

            </a>

        </div>
    </div>
    </div>
</section>
