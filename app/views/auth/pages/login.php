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
<header class="mb-4">
    <h3 class="fw-bold">Connexion</h3>
    <p class="text-muted small">Veuillez remplir vos informations.</p>
</header>

<main>
    <form action="/login" method="POST" id="loginForm" class="row g-3" novalidate>
        <div id="formStatus" class="alert d-none small"></div>

        <div class="col-12">
            <label for="email" class="form-label fw-semibold small">Email</label>
            <input type="email" class="form-control bg-light <?= isInvalid('email', $errors) ?>"
                   name="email" id="email" value="<?= formatText($values['email']) ?>"
                   placeholder="exemple@domaine.com" required>
            <div class="invalid-feedback" id="emailError">
                <?= formatText($errors['email']) ?>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex col-12">
                <label for="password" class="form-label fw-semibold small col-6">Mot de passe</label>
                <p class="small text-muted mb-0 text-end col-6">
                    <a href="/resetPassword" class="text-decoration-none">Oublié ?</a>
                </p>
            </div>
            <input type="password" class="form-control bg-light <?= isInvalid('password', $errors) ?>"
                   name="password" id="password" required placeholder="••••••••••">
            <div class="invalid-feedback" id="passwordError">
                <?= formatText($errors['password']) ?>
            </div>
        </div>

        <div class="col-12">
            <button class="btn btn-primary w-100 fw-bold py-2 mt-2 shadow-sm border-0 custom-btn" type="submit">
                Se connecter
            </button>
        </div>
    </form>
</main>
