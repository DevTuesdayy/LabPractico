<div class="app-shell">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="app-main">
        <?php require __DIR__ . '/../partials/header.php'; ?>
        <main class="page-content">
            <section class="home-intro">
                <p class="eyebrow">BIENVENIDO A DOCUFLOW</p>
                <h1>Los documentos de tu oficina, en un solo lugar.</h1>
                <p class="muted">Registra oficios, consulta su información y descarga los archivos que necesitas.</p>
                <a class="button" href="index.php?page=documentos">Ver documentos →</a>
            </section>
            <div class="home-links">
                <a class="panel home-link" href="index.php?page=documentos">
                    <span class="home-link-icon" aria-hidden="true">▤</span>
                    <h2>Consultar documentos</h2>
                    <p class="muted">Busca un oficio y revisa sus datos y su PDF adjunto.</p>
                    <span class="link-label">Abrir bandeja →</span>
                </a>
                <a class="panel home-link" href="index.php?page=registrar-documento">
                    <span class="home-link-icon" aria-hidden="true">+</span>
                    <h2>Registrar documento</h2>
                    <p class="muted">Agrega un oficio recibido y adjunta su archivo PDF.</p>
                    <span class="link-label">Nuevo registro →</span>
                </a>
            </div>
        </main>
    </div>
</div>
