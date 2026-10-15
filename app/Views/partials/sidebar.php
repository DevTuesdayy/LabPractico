<?php $paginaActual = $_GET['page'] ?? 'documentos'; ?>
<aside class="sidebar">
    <a class="sidebar-brand" href="index.php?page=inicio">
        <span class="brand-logo" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M8 13h8M8 17h5"/></svg></span>
        <span class="brand-info"><strong>DocuFlow</strong><small>Gestión documental</small></span>
    </a>
    <nav class="sidebar-navigation" aria-label="Navegación principal">
        <a class="navigation-link" href="index.php?page=inicio" <?= $paginaActual === 'inicio' ? 'aria-current="page"' : '' ?>>
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg>
            <span>Inicio</span>
        </a>
        <a class="navigation-link" href="index.php?page=documentos" <?= in_array($paginaActual, ['documentos', 'detalle-documento'], true) ? 'aria-current="page"' : '' ?>>
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zM3 11h18"/></svg>
            <span>Documentos</span>
        </a>
        <a class="navigation-link" href="index.php?page=registrar-documento" <?= $paginaActual === 'registrar-documento' ? 'aria-current="page"' : '' ?>>
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="4"/><path d="M12 8v8M8 12h8"/></svg>
            <span>Nuevo documento</span>
        </a>
    </nav>
</aside>
