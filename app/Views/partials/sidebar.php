<!-- sidebar.php: navegación lateral reutilizable de DocuFlow. -->
<aside class="sidebar">

    <div class="sidebar-brand">
        <!-- Logotipo y nombre de la aplicacion-->
        <div class="brand-logo" aria-hidden="true">▤</div>

        <div class="brand-info">
            <h1>DocuFlow</h1>
            <p>GESTIÓN DE OFICIOS</p>
        </div>
    </div>

    <div class="sidebar-search">
        <!-- Buscador de Documentos -->
        <form class="search-form">
            <span class="search-icon" aria-hidden="true">⌕</span>

            <input type="search" placeholder="Buscar Oficio...">

            <button type="submit" aria-label="Buscar">⌘K</button>
        </form>
    </div>

    <nav class="sidebar-navigation">

        <section class="navigation-section">
            <!-- Titulo principal -->
            <h2>PRINCIPAL</h2>

            <a class="navigation-link" href="index.php?page=inicio">
                <span class="nav-icon">⌂</span>
                <span>Panel Principal</span>
            </a>
            <a class="navigation-link active" href="index.php?page=documentos">
                <span class="nav-icon">▣</span>
                <span>Registro de Oficios</span>
            </a>
            <a class="navigation-link" href="index.php?page=documentos#buscador">
                <span class="nav-icon">▤</span>
                <span>Bandeja de Entrada</span>
            </a>
            <a class="navigation-link" href="index.php?page=documentos#seguimiento">
                <span class="nav-icon">⚯</span>
                <span>Seguimiento y Turnados</span>
            </a>

        </section>

        <section class="navigation-section administration-section">
            <h2>ADMINISTRACIÓN</h2>
            <a class="navigation-link" href="#reportes">
                <span class="nav-icon">▥</span>
                <span>Reportes y Estadísticas</span>
            </a>
            <a class="navigation-link" href="#catalogos">
                <span class="nav-icon">▤</span>
                <span>Catálogos y Áreas</span>
            </a>
            <a class="navigation-link" href="#configuracion">
                <span class="nav-icon">⚙</span>
                <span>Configuración</span>
            </a>
        </section>

    </nav>

    <div class="sidebar-footer">
        <!-- Informacion inferior del sistema -->
        <span class="status-dot">
            <!-- Indicador de estado -->
        </span>

        <div class="footer-info">
            <strong>Folios 2024</strong>
            <span>Activo / Serie A</span>
        </div>
    </div>
</aside>