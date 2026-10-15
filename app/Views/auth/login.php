<?php
$error = $_SESSION['login_error'] ?? '';
$email = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_email']);
?>

<main class="login-shell">
    <section class="login-intro" aria-label="DocuFlow">
        <a class="login-brand" href="index.php?page=login">
            <span class="login-brand-icon" aria-hidden="true">▤</span>
            <span>DocuFlow<small>GESTIÓN DOCUMENTAL</small></span>
        </a>
        <div class="login-intro-copy">
            <span class="login-kicker">OFICINA DIGITAL</span>
            <h1>Tu gestión documental empieza aquí.</h1>
            <p>Consulta tus oficios y mantén el seguimiento de los documentos de tu oficina en un solo lugar.</p>
        </div>
        <span class="login-intro-footer">Un espacio para organizar tu trabajo.</span>
    </section>

    <section class="login-form-panel" aria-labelledby="login-title">
        <div class="login-form-content">
            <span class="login-kicker">BIENVENIDO</span>
            <h2 id="login-title">Iniciar sesión</h2>
            <p class="login-description">Ingresa con la cuenta que te proporcionó el administrador.</p>

            <?php if ($error !== ''): ?>
                <div class="login-error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=login" method="post" class="login-form">
                <input type="hidden" name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" maxlength="120" required
                    autocomplete="username" placeholder="nombre@oficina.com"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required
                    autocomplete="current-password">

                <button type="submit">Entrar al sistema <span aria-hidden="true">→</span></button>
            </form>

            <p class="login-help">Si necesitas una cuenta o recuperar tu acceso, contacta al administrador.</p>
        </div>
        <small class="login-footer">DocuFlow · Gestión de oficios y documentos</small>
    </section>
</main>
