<?php
$success = $success ?? false;
$user = $user ?? null;
$errors = $errors ?? ['email' => '', 'password' => ''];
$values = $values ?? ['email' => ''];

if ($success) {
    sleep(1);

    Flight::redirect('/accueil');
}
?>
<section class="section-padding bngrc-login-section">
  <div class="container">
    <div class="row align-items-center g-4">

      <div class="col-lg-6 col-12">
        <div class="bngrc-login-hero">
          <span class="bngrc-login-pill">
            <i class="bi-shield-lock me-1"></i> Accès sécurisé
          </span>

          <h2 class="mt-3 mb-2 bngrc-login-title">Connexion Admin</h2>
          <p class="bngrc-login-sub mb-0">
            Accédez à l’espace de gestion des dons et des distributions.
          </p>
        </div>
      </div>

      <div class="col-lg-6 col-12">
        <div class="bngrc-login-card">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bngrc-login-icon">
              <i class="bi-person-lock"></i>
            </div>
            <div>
              <h5 class="mb-0 fw-bold">Se connecter</h5>
              <small class="text-muted">Administrateurs uniquement</small>
            </div>
          </div>

          <form method="post" action="/login">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="admin@bngrc.mg" required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Mot de passe</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi-key"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
              </div>

              <a href="/reset-password" class="bngrc-login-link small">Mot de passe oublié ?</a>
            </div>

            <!-- bouton KindHeart -->
            <button type="submit" class="custom-btn btn w-100 bngrc-login-btn">
              Connexion
              <i class="bi-arrow-right ms-1"></i>
            </button>

            <div class="bngrc-login-note mt-3">
              <i class="bi-info-circle me-1"></i>
              Accès réservé. En cas de besoin, contactez l’administrateur.
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>
