<?php $paginaActual = $_GET['page'] ?? 'documentos'; ?>
<aside class="sidebar">
    <a class="sidebar-brand" href="index.php?page=inicio">
        <span class="brand-logo" aria-hidden="true">▤</span>
        <span class="brand-info"><strong>DocuFlow</strong><small>Gestión documental</small></span>
    </a>
    <nav class="sidebar-navigation" aria-label="Navegación principal">
        <a class="navigation-link" href="index.php?page=inicio" <?= $paginaActual === 'inicio' ? 'aria-current="page"' : '' ?>>Inicio</a>
        <a class="navigation-link" href="index.php?page=documentos" <?= in_array($paginaActual, ['documentos', 'detalle-documento'], true) ? 'aria-current="page"' : '' ?>>Documentos</a>
        <a class="navigation-link" href="index.php?page=registrar-documento" <?= $paginaActual === 'registrar-documento' ? 'aria-current="page"' : '' ?>>Nuevo documento</a>
    </nav>
</aside>
