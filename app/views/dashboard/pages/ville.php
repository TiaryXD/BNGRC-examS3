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
                        <div class="city-image-wrapper">
                            <img src="/assets/images/cities/<?= htmlspecialchars($ville['nom']) ?>.png"
                                class="custom-block-image img-fluid city-image"
                                alt="<?= htmlspecialchars($ville['nom']) ?>">
                        </div>


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
