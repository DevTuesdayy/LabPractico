<div class="app-shell">
        <?php require __DIR__ . '/../partials/sidebar.php'; ?>
        <div class="app-main">
            <?php require __DIR__ . '/../partials/header.php'; ?>
            <main class="dashboard-content" id="documentos">
                <section class="page-intro">
                    <div class="intro-content">
                        <span class="section-badge">
                            Gestión Documental Centralizada
                        </span>

                        <h1>Bandeja General de Oficios y Documentos</h1>
                        <p>Consulta, filtra, organiza y da seguimiento a toda la correspondencia recibida y remitida con
                            trazabilidad en
                            tiempo real.</p>
                    </div>

                    <div class="intro-actions">

                        <button class="export-button">
                            <!-- Icono de descarga -->
                            Exportar reporte
                        </button>

                        <a class="new-office-button" href="index.php?page=registrar-documento">
                            <!-- Icono de agregar -->
                            Registrar Nuevo Oficio
                        </a>

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
                                placeholder="Buscar por No. de oficio, asunto, remitente, área destino o palabra clave...">

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
                                <input type="text" name="desde" id="desde" value="01/10/2026" aria-label="Fecha desde">
                            </div>

                            <div class="filters-group">
                                <label for="hasta">HASTA</label>
                                <input type="text" name="hasta" id="hasta" value="06/10/2026" aria-label="Fecha hasta">
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
                                <label for="area-destino">ÁREA DESTINATARIA</label>
                                <select name="area" id="area-destino">
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
                                <div class="active-filters"><label>FILTROS ACTIVOS</label><span>Periodo: Octubre 2026
                                        ×</span><span>Área: Todas ×</span></div>
                                <button type="button" class="clear-filters">Limpiar filtros</button>
                                <button type="submit" class="apply-filters">⚑ Aplicar Filtros</button>
                            </div>
                        </div>
                    </form>
                </section>

<section class="documents-panel" id="documentos-listado">
                    <div class="documents-toolbar">
                        <div class="bulk-actions">
                            <label class="select-visible">
                                <input type="checkbox" aria-label="Seleccionar documentos visibles">
                                <span>Seleccionar visibles (10)</span>
                            </label>
                            <span class="toolbar-divider"></span>
                            <button type="button" class="toolbar-button">⚯ <span>Turnar en lote</span></button>
                            <button type="button" class="toolbar-button">▣ <span>Archivar</span></button>
                        </div>

                        <div class="table-legend">
                            <span><i class="legend-dot attachment-dot"></i> Con archivo adjunto (PDF)</span>
                            <span><i class="legend-dot urgent-dot"></i> Prioridad urgente</span>
                        </div>
                    </div>

                    <div class="documents-table-wrapper">
                        <table class="documents-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No. de oficio</th>
                                    <th>Fecha / plazo</th>
                                    <th>Asunto y descripción</th>
                                    <th>Remitente</th>
                                    <th>Área destino</th>
                                    <th>Prioridad</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" aria-label="Seleccionar OF-001/2026"></td>
                                    <td><span class="office-number">OF-001/2026</span><span
                                            class="attachment-icon">⌕</span></td>
                                    <td><strong>05/10/2026</strong><small>Vence: 15/10/2026</small></td>
                                    <td><strong>Solicitud de material para laboratorios de
                                            ciencias</strong><small>Requisición trimestral de consumibles e insumos
                                            para...</small></td>
                                    <td><strong>Dirección Administrativa</strong><small>Ing. Roberto Galván</small></td>
                                    <td><span class="area-value"><i></i>Coordinación Académica</span></td>
                                    <td><span class="priority normal">Normal</span></td>
                                    <td><span class="status received"><i></i>Recibido</span></td>
                                    <td>
                                        <div class="row-actions"><button aria-label="Ver documento">◉</button><button
                                                aria-label="Descargar documento">⇩</button><button
                                                aria-label="Turnar documento">⚯</button><button
                                                aria-label="Más opciones">⋮</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="Seleccionar OF-002/2026"></td>
                                    <td><span class="office-number">OF-002/2026</span><span
                                            class="attachment-icon">⌕</span></td>
                                    <td><strong>04/10/2026</strong><small>Vence: 12/10/2026</small></td>
                                    <td><strong>Invitación protocolaria a Simposio de
                                            Innovación</strong><small>Participación en panel magistral con directivos
                                            instit...</small></td>
                                    <td><strong>Secretaría General</strong><small>Dra. Claudia Morales</small></td>
                                    <td><span class="area-value"><i></i>Dirección ETDA</span></td>
                                    <td><span class="priority medium">Media</span></td>
                                    <td><span class="status in-progress"><i></i>En proceso</span></td>
                                    <td>
                                        <div class="row-actions"><button aria-label="Ver documento">◉</button><button
                                                aria-label="Descargar documento">⇩</button><button
                                                aria-label="Turnar documento">⚯</button><button
                                                aria-label="Más opciones">⋮</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="Seleccionar OF-003/2026"></td>
                                    <td><span class="office-number">OF-003/2026</span><span
                                            class="attachment-icon">⌕</span></td>
                                    <td><strong>03/10/2026</strong><small class="resolved-text">Resuelto: 05/10</small>
                                    </td>
                                    <td><strong>Reporte mensual de actividades e infraestructura</strong><small>Cierre
                                            trimestral de disponibilidad de servidores y m...</small></td>
                                    <td><strong>Departamento de Sistemas</strong><small>Lic. Andrea Flores</small></td>
                                    <td><span class="area-value"><i></i>Dirección ETDA</span></td>
                                    <td><span class="priority normal">Normal</span></td>
                                    <td><span class="status attended"><i></i>Atendido</span></td>
                                    <td>
                                        <div class="row-actions"><button aria-label="Ver documento">◉</button><button
                                                aria-label="Descargar documento">⇩</button><button
                                                aria-label="Turnar documento">⚯</button><button
                                                aria-label="Más opciones">⋮</button></div>
                                    </td>
                                </tr>
                                <tr class="urgent-row">
                                    <td><input type="checkbox" aria-label="Seleccionar OF-004/2026"></td>
                                    <td><span class="office-number urgent-number">OF-004/2026</span><span
                                            class="urgent-mark">!</span></td>
                                    <td><strong>02/10/2026</strong><small class="urgent-text">◷ Vence hoy</small></td>
                                    <td><strong>Auditoría externa de subsidios federales
                                            2026</strong><small>Requerimiento inaplazable de entrega de pólizas y
                                            e...</small></td>
                                    <td><strong>Institución Externa (ASF)</strong><small>Auditoría Superior</small></td>
                                    <td><span class="area-value urgent-area"><i></i>Rectoría / Abogado Gral.</span></td>
                                    <td><span class="priority high">Alta</span></td>
                                    <td><span class="status urgent"><i></i>Urgente</span></td>
                                    <td>
                                        <div class="row-actions"><button aria-label="Ver documento">◉</button><button
                                                aria-label="Descargar documento">⇩</button><button
                                                aria-label="Turnar documento">⚯</button><button
                                                aria-label="Más opciones">⋮</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="Seleccionar OF-005/2026"></td>
                                    <td><span class="office-number">OF-005/2026</span><span
                                            class="attachment-icon">⌕</span></td>
                                    <td><strong>01/10/2026</strong><small>Vence: 20/10/2026</small></td>
                                    <td><strong>Convenio marco de colaboración interinstitucional</strong><small>Acuerdo
                                            para residencias profesionales y servicio so...</small></td>
                                    <td><strong>H. Ayuntamiento Municipal</strong><small>Secretaría de
                                            Desarrollo</small></td>
                                    <td><span class="area-value"><i></i>Rectoría General</span></td>
                                    <td><span class="priority medium">Media</span></td>
                                    <td><span class="status in-progress"><i></i>En proceso</span></td>
                                    <td>
                                        <div class="row-actions"><button aria-label="Ver documento">◉</button><button
                                                aria-label="Descargar documento">⇩</button><button
                                                aria-label="Turnar documento">⚯</button><button
                                                aria-label="Más opciones">⋮</button></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="Seleccionar OF-006/2026"></td>
                                    <td><span class="office-number">OF-006/2026</span><span
                                            class="attachment-icon">⌕</span></td>
                                    <td><strong>30/09/2026</strong><small>Vence: 14/10/2026</small></td>
                                    <td><strong>Convocatoria para Proyectos de Investigación</strong><small>Bases de
                                            financiamiento y cronograma de recepción...</small></td>
                                    <td><strong>Consejo Estatal de Ciencia</strong><small>Mtro. Daniel Ramos</small>
                                    </td>
                                    <td><span class="area-value"><i></i>Coord. de Investigación</span></td>
                                    <td><span class="priority normal">Normal</span></td>
                                    <td><span class="status received"><i></i>Recibido</span></td>
                                    <td>
                                        <div class="row-actions"><button aria-label="Ver documento">◉</button><button
                                                aria-label="Descargar documento">⇩</button><button
                                                aria-label="Turnar documento">⚯</button><button
                                                aria-label="Más opciones">⋮</button></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="documents-footer">
                        <p>Mostrando <strong>1 a 6</strong> de <strong>148</strong> oficios registrados</p>
                        <label>Filas: <select>
                                <option>10</option>
                                <option>20</option>
                                <option>50</option>
                            </select></label>
                        <nav class="pagination" aria-label="Paginación">
                            <button disabled>«</button><button disabled>‹</button><button
                                class="current">1</button><button>2</button><button>3</button><span>...</span><button>15</button><button>›</button><button>»</button>
                        </nav>
                    </div>
                </section>
            </main>
        </div>
    </div>
