<?php
$documentos = $datos['documentos'];
$resumen = $datos['resumen'];
$clasesEstado = ['Recibido' => 'received', 'En proceso' => 'in-progress', 'Atendido' => 'attended'];
$escapar = fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<div class="app-shell">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="app-main">
        <?php require __DIR__ . '/../partials/header.php'; ?>
        <main class="page-content" id="documentos">
            <div class="page-heading">
                <div>
                    <p class="eyebrow">TU OFICINA, ORGANIZADA</p>
                    <h1>Documentos</h1>
                    <p class="muted">Consulta los oficios recibidos y abre sus datos y archivos adjuntos.</p>
                </div>
                <a class="button" href="index.php?page=registrar-documento">+ Nuevo documento</a>
            </div>

            <section class="summary-cards" aria-label="Resumen de documentos">
                <?php foreach (['total' => 'Total', 'recibidos' => 'Recibidos', 'en_proceso' => 'En proceso', 'atendidos' => 'Atendidos'] as $clave => $etiqueta): ?>
                    <article class="summary-card">
                        <h2><?= $etiqueta ?></h2>
                        <strong><?= (int) $resumen[$clave] ?></strong>
                    </article>
                <?php endforeach; ?>
            </section>

            <section class="panel" aria-labelledby="listado-title">
                <div class="panel-heading">
                    <h2 id="listado-title">Bandeja de documentos</h2>
                    <span class="muted"><?= count($documentos) ?> registrados</span>
                </div>
                <div class="document-filters" id="buscador">
                    <div class="field">
                        <label for="search-documentos">Buscar documento</label>
                        <input type="search" id="search-documentos" placeholder="Oficio, asunto, remitente o destino">
                    </div>
                    <div class="field">
                        <label for="estado-documentos">Estado</label>
                        <select id="estado-documentos">
                            <option value="">Todos los estados</option>
                            <option>Recibido</option>
                            <option>En proceso</option>
                            <option>Atendido</option>
                        </select>
                    </div>
                </div>

                <table class="documents-table">
                    <caption class="visually-hidden">Documentos recibidos y acceso a su detalle</caption>
                    <thead>
                        <tr>
                            <th scope="col">Documento</th>
                            <th scope="col">Origen y destino</th>
                            <th scope="col">Recepción</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documentos as $documento): ?>
                            <tr data-documento data-estado="<?= $escapar($documento['estado']) ?>">
                                <td data-label="Documento" class="document-main-cell">
                                    <span class="office-number"><?= $escapar($documento['no_oficio']) ?></span>
                                    <strong class="document-subject"><?= $escapar($documento['asunto']) ?></strong>
                                </td>
                                <td data-label="Origen y destino">
                                    <span class="cell-line"><span class="cell-caption">De:</span> <?= $escapar($documento['remitente']) ?></span>
                                    <span class="cell-line"><span class="cell-caption">Para:</span> <?= $escapar($documento['destinatario']) ?></span>
                                </td>
                                <td data-label="Recepción">
                                    <time datetime="<?= $escapar($documento['fecha_recepcion']) ?>"><?= $escapar($documento['fecha_recepcion']) ?></time>
                                </td>
                                <td data-label="Estado">
                                    <span class="status <?= $clasesEstado[$documento['estado']] ?? 'received' ?>"><?= $escapar($documento['estado']) ?></span>
                                </td>
                                <td data-label="Acciones" class="document-action-cell">
                                    <a class="button button-secondary button-small" href="index.php?page=detalle-documento&amp;id=<?= (int) $documento['id_documento'] ?>" aria-label="Ver detalle del oficio <?= $escapar($documento['no_oficio']) ?>">Ver detalle</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr id="sin-resultados" <?= count($documentos) > 0 ? 'hidden' : '' ?>>
                            <td colspan="5" class="empty-state"><?= empty($documentos) ? 'Todavía no hay documentos. Registra el primero para comenzar.' : 'No hay documentos que coincidan con la búsqueda.' ?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="documents-footer" id="documentos-count" aria-live="polite">Mostrando <?= count($documentos) ?> de <?= count($documentos) ?> documentos</p>
            </section>
        </main>
    </div>
</div>
