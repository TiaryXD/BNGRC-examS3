<?php
$ville   = $ville ?? null;
$besoin = $besoin ?? [];
$distribution = $distribution ?? [];
$besoins_couverture = $besoins_couverture ?? [];


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


        <div class="row g-4">
            <?php if (empty($besoin)): ?>
                <div class="col-12">
                <div class="alert alert-info mb-0">Aucun besoin enregistré pour cette ville.</div>
                </div>
            <?php endif; ?>

            <?php foreach ($besoin as $b): 
                $quantite = (float)$b['quantite'];
                $distrib  = (float)$b['total_distribue'];
                $reste    = max(0, (float)$b['reste']); // sécurité
                $pct = ($quantite > 0) ? min(100, ($distrib / $quantite) * 100) : 0;
            ?>
                <div class="col-lg-6 col-12">
                <div class="custom-block-wrap h-100">
                    <div class="custom-block-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                        <h5 class="mb-1"><?= htmlspecialchars($b['description']) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($b['type_nom']) ?></small>
                        </div>

                        <?php if ($reste <= 0): ?>
                        <span class="badge bg-success">Couvert</span>
                        <?php else: ?>
                        <span class="badge bg-warning text-dark">Reste</span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-2 small text-muted">
                        Besoin total : <strong><?= number_format($quantite, 0, ',', ' ') ?> <?= htmlspecialchars($b['unite']) ?></strong><br>
                        Déjà distribué : <strong><?= number_format($distrib, 0, ',', ' ') ?> <?= htmlspecialchars($b['unite']) ?></strong><br>
                        Reste à couvrir : <strong><?= number_format($reste, 0, ',', ' ') ?> <?= htmlspecialchars($b['unite']) ?></strong>
                    </div>

                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= (float)$pct ?>%"></div>
                    </div>

                    <?php if (!empty($b['remarque'])): ?>
                        <div class="small text-muted">
                        <i class="bi-chat-left-text me-1"></i><?= htmlspecialchars($b['remarque']) ?>
                        </div>
                    <?php endif; ?>

                    </div>

                    <div class="custom-block-footer p-3 d-flex justify-content-end">
                    <?php if ($reste <= 0): ?>
                        <button class="btn btn-secondary btn-sm" disabled>
                        <i class="bi-check2-circle me-1"></i> Déjà couvert
                        </button>
                    <?php else: ?>
                        <a href="/distribuer?don=<?= urlencode($b['description']) ?>&unite=<?= urlencode($b['unite']) ?>&ville_id=<?= (int)$ville['id'] ?>"
                        class="btn btn-sm custom-btn">
                        <i class="bi-send me-1"></i> Distribuer
                        </a>
                    <?php endif; ?>
                    </div>

                </div>
                </div>
            <?php endforeach; ?>
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

            <a href="/ajout-besoin/<?= (int)$ville['id'] ?>"
            class="custom-btn btn btn-warning px-4 py-2">

                <i class="bi-plus-circle me-2"></i>
                Ajouter un besoin pour cette ville

            </a>

        </div>
    </div>
    </div>
</section>
