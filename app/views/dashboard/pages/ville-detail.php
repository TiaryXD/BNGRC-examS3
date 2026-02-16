<?php
$ville   = $ville ?? null;
$besoin = $besoin ?? [];

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


<div class="row mb-4">
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
</section>
