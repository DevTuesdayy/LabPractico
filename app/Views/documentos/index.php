<section class="page-intro">
    <div class="intro-content">
        <span class="section-badge">
            Gestion Documental Centralizada
        </span>

        <h1>Bandeja General de Oficios y Documentos</h1>
        <p>Consulta, filtra, organiza y da seguimiento a toda la correspondencia recibida y remitida con trazabilidad en
            tiempo real.</p>
    </div>

    <div class="intro-actions">

        <button class="export-button">
            <!-- Icono de descarga -->
            Exportar reporte
        </button>

        <button class="new-office-button">
            <!-- Icono de agregar -->
            Registrar Nuevo Oficio
        </button>

    </div>

</section>

<section class="summary-cards">

    <article class="summary-card">

        <div class="card-content">
            <!-- Total en bandeja -->
            <h3>TOTAL EN BANDEJA</h3>
            <strong>148</strong>
            <p>+14 Hoy</p>
            <!-- Icono de bandeja -->
            <div class="card-icon"></div>

        </div>
    </article>

    <article class="summary-card">
        <!-- Total en tramite -->
        <div class="card-content">
            <h3>EN TRAMITE</h3>
            <strong>42</strong>
            <p>18 Turnos</p>
            <!-- Icono de tramite -->
            <div class="card-icon"></div>
        </div>
    </article>

    <article class="summary-card">
        <!-- Atendidos / Resueltos -->
        <div class="card-content">
            <h3>ATENDIDOS / RESUELTOS</h3>
            <strong>97</strong>
            <p>+92% Hoy a tiempo</p>
            <!-- Icono Resuelto -->
            <div class="card-icon"></div>
        </div>

    </article>

    <article class="summary-card">
        <!-- Urgentes / Vencen -->
        <div class="card-content">
            <h3>URGENTES / VENCEN</h3>
            <strong>9</strong>
            <p>+2 Hoy</p>
            <!-- Icono Urgente -->
            <div class="card-icon"></div>
        </div>
    </article>

</section>

<section class="filters-panel" id="buscador">

    <form class="filter-form">
        <div class="search-row">
            <!-- Aqui va el buscador -->

            <span class="search-icon">
                <!-- Icono de busqueda -->
            </span>

            <input type="search" name="search"
                placeholder="Buscar por No. de Oficio, remitente, área o destino o palabra clave...">

        </div>

        <div class="filter-rows">
            <!-- Aquí van los filtros -->

            <div class="filters-group">
                <label for="status">ESTATUS</label>

                <select name="estatus" id="status">
                    <option>Todos los estatus</option>
                    <option>Recibido</option>
                    <option>En proceso</option>
                    <option>Turnado</option>
                    <option>Agrupado</option>
                    <option>Atendido</option>
                    <option>Urgente</option>
                </select>
            </div>

            <div class="filters-group">
                <label for="desde">DESDE</label>
                <select name="desde" id="desde">
                    <!-- Aqui va el calendario -->
                </select>
            </div>

            <div class="filters-group">
                <label for="hasta">HASTA</label>
                <select name="hasta" id="hasta">
                    <!-- Aqui va el calendario -->
                </select>
            </div>

            <div class="filters-group">
                <label for="tipo">TIPO</label>
                <select name="tipo" id="tipo">
                    <option>Todos los tipos</option>
                    <option>Oficio Ordinario</option>
                    <option>Circular</option>
                    <option>Memorandum</option>
                    <option>Peticion Externa</option>
                </select>
            </div>

            <div class="filters-group">
                <label for="area">AREA</label>
                <select name="area" id="area">
                    <option>Todos las areas</option>
                    <option>Rectoría General</option>
                    <option>Dirección Administrativa</option>
                    <option>Coordinación Académica</option>
                    <option>Depto. de Sistemas</option>
                    <option>Coordinación de Investigación</option>
                </select>
            </div>

        </div>

        <div class="filter-actions">
            <!-- Aqui van las acciones -->
            <div class=" filters-group">
                <label for="filtros-activos">FILTROS ACTIVOS</label>

                <button type="button" id="filtros-activos">
                    <!-- Icono de filtros activos -->
                    Ver filtros activos
                </button>
            </div>
        </div>
    </form>
</section>
