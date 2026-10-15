<header class="header">
    <span class="header-title">Gestión documental</span>
    <div class="header-actions">
        <span class="session-user"><?= htmlspecialchars($_SESSION['usuario']['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php require __DIR__ . '/logout.php'; ?>
    </div>
</header>
