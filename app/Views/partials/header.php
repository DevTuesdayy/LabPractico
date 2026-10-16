<header class="header">
    <span class="header-title">Gestión documental</span>
    <div class="header-actions">
        <span class="session-user">
            <span class="session-avatar" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="12" cy="8" r="3.5"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/></svg></span>
            <?= htmlspecialchars($_SESSION['usuario']['nombre'], ENT_QUOTES, 'UTF-8') ?>
        </span>
        <?php require __DIR__ . '/logout.php'; ?>
    </div>
</header>
