<section class="section-padding">
    <div class="container">

        <!-- TITLE -->
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto text-center mb-5">
                <h2 class="mb-3">Villes sinistrées</h2>
                <p class="text-muted">
                    Liste des communes affectées et suivi des besoins humanitaires.
                </p>
            </div>
        </div>

        <div class="row">

            <?php if (empty($villes)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Aucune ville enregistrée.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($villes as $ville): ?>

                <div class="col-lg-4 col-md-6 col-12 mb-4">

                    <div class="custom-block-wrap">

                        <!-- IMAGE -->
                        <img src="/assets/images/cities/default-city.jpg"
                             class="custom-block-image img-fluid"
                             alt="<?= htmlspecialchars($ville['nom']) ?>">

                        <div class="custom-block">

                            <!-- REGION -->
                            <div class="custom-block-body">

                                <small class="text-muted">
                                    <i class="bi-geo-alt"></i>
                                    <?= htmlspecialchars($ville['region_nom'] ?? 'Région inconnue') ?>
                                </small>

                                <h5 class="mb-3 mt-2">
                                    <?= htmlspecialchars($ville['nom']) ?>
                                </h5>

                                <!-- STATS -->
                                <ul class="list-unstyled small mb-3">

                                    <li>
                                        <i class="bi-clipboard-data text-warning"></i>
                                        <strong>Besoins :</strong>
                                        <?= (int)$ville['nb_besoins'] ?>
                                    </li>

                                    <li>
                                        <i class="bi-exclamation-triangle text-danger"></i>
                                        <strong>Total demandé :</strong>
                                        <?= number_format($ville['besoin_total'],0,',',' ') ?>
                                    </li>

                                    <li>
                                        <i class="bi-box-seam text-success"></i>
                                        <strong>Reste :</strong>
                                        <?= number_format($ville['besoin_restant_total'],0,',',' ') ?>
                                    </li>

                                </ul>

                                <!-- PROGRESS BAR -->
                                <?php
                                $progress = 0;
                                if ($ville['besoin_total'] > 0) {
                                    $progress =
                                        100 - (
                                            ($ville['besoin_restant_total']
                                            / $ville['besoin_total']) * 100
                                        );
                                }
                                ?>

                                <div class="progress mb-3" style="height:8px;">
                                    <div class="progress-bar bg-warning"
                                         role="progressbar"
                                         style="width: <?= max(0,min(100,$progress)) ?>%">
                                    </div>
                                </div>

                            </div>

                            <!-- BUTTON -->
                            <div class="custom-block-footer">
                                <a href="/ville/<?= $ville['id'] ?>"
                                   class="custom-btn btn">
                                    Voir les besoins
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>
