<?php
$documento = $datos['documento'];
$escapar = fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$clasesEstado = ['Recibido' => 'received', 'En proceso' => 'in-progress', 'Atendido' => 'attended'];
?>
<div class="app-shell">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="app-main">
        <?php require __DIR__ . '/../partials/header.php'; ?>
        <main class="page-content">
            <a class="back-link" href="index.php?page=documentos">← Volver a documentos</a>
            <div class="page-heading">
                <div>
                    <p class="eyebrow"><?= $escapar($documento['no_oficio']) ?></p>
                    <h1><?= $escapar($documento['asunto']) ?></h1>
                </div>
                <span class="status <?= $clasesEstado[$documento['estado']] ?? 'received' ?>"><?= $escapar($documento['estado']) ?></span>
            </div>

            <?php if ($datos['mensajeDocumento'] !== null): ?>
                <div class="form-feedback state-feedback <?= $escapar($datos['mensajeDocumento']['tipo']) ?>" role="<?= $datos['mensajeDocumento']['tipo'] === 'error' ? 'alert' : 'status' ?>">
                    <?= $escapar($datos['mensajeDocumento']['mensaje']) ?>
                </div>
            <?php endif; ?>

            <div class="detail-layout">
                <section class="panel detail-card" aria-labelledby="detalle-title">
                    <div class="detail-card-heading">
                        <h2 id="detalle-title">Información del documento</h2>
                        <a class="button button-secondary button-small" href="index.php?page=editar-documento&amp;id=<?= (int) $documento['id_documento'] ?>">Editar datos</a>
                    </div>
                    <dl class="detail-fields">
                        <div><dt>Número de oficio</dt><dd><?= $escapar($documento['no_oficio']) ?></dd></div>
                        <div><dt>Fecha de emisión</dt><dd><?= $escapar($documento['fecha_emision']) ?></dd></div>
                        <div><dt>Fecha de recepción</dt><dd><?= $escapar($documento['fecha_recepcion']) ?></dd></div>
                        <div><dt>Remitente</dt><dd><?= $escapar($documento['remitente']) ?></dd></div>
                        <div><dt>Destinatario</dt><dd><?= $escapar($documento['destinatario']) ?></dd></div>
                        <div><dt>Registrado por</dt><dd><?= $escapar($documento['registrado_por']) ?></dd></div>
                        <div class="wide"><dt>Asunto</dt><dd class="detail-subject"><?= $escapar($documento['asunto']) ?></dd></div>
                    </dl>
                </section>

                <div class="detail-sidebar">
                <aside class="panel detail-card" aria-labelledby="adjunto-title">
                    <span class="pdf-badge" aria-hidden="true">PDF</span>
                    <h2 id="adjunto-title">Archivo adjunto</h2>
                    <?php if ($datos['archivoDisponible']): ?>
                        <p class="file-title">PDF del oficio <?= $escapar($documento['no_oficio']) ?></p>
                        <p class="muted"><?= number_format((int) $datos['archivoTamano'] / 1024, 0, ',', '.') ?> KB</p>
                        <a class="button button-secondary download-button" data-pdf-preview href="index.php?page=ver-pdf&amp;id=<?= (int) $documento['id_documento'] ?>" target="_blank" rel="noopener">Vista previa</a>
                        <a class="button download-button" href="index.php?page=descargar-documento&amp;id=<?= (int) $documento['id_documento'] ?>">Descargar PDF</a>
                    <?php else: ?>
                        <p class="muted">El archivo adjunto no está disponible.</p>
                    <?php endif; ?>
                </aside>
                <section class="panel detail-card" aria-labelledby="estado-title">
                    <h2 id="estado-title">Estado del documento</h2>
                    <form action="index.php?page=actualizar-estado" method="post" class="state-form">
                        <input type="hidden" name="csrf_token" value="<?= $escapar($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="id_documento" value="<?= (int) $documento['id_documento'] ?>">
                        <div class="field">
                            <label for="id_estatus">Estado</label>
                            <select name="id_estatus" id="id_estatus" required>
                                <?php foreach ($datos['estatus'] as $estatus): ?>
                                    <option value="<?= (int) $estatus['id_estatus'] ?>" <?= (int) $estatus['id_estatus'] === (int) $documento['id_estatus'] ? 'selected' : '' ?>><?= $escapar($estatus['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="button">Guardar estado</button>
                    </form>
                </section>
                <section class="panel detail-card" aria-labelledby="eliminar-title">
                    <h2 id="eliminar-title">Eliminar documento</h2>
                    <p class="muted file-title">Elimina este registro, su historial y el PDF adjunto.</p>
                    <a class="button button-danger download-button" href="index.php?page=eliminar-documento&amp;id=<?= (int) $documento['id_documento'] ?>">Eliminar documento</a>
                </section>
                </div>
            </div>

            <section class="panel detail-card history-card" aria-labelledby="historial-title">
                <h2 id="historial-title">Historial de estados</h2>
                <?php if (empty($datos['historial'])): ?>
                    <p class="muted history-empty">No hay movimientos registrados.</p>
                <?php else: ?>
                    <ol class="status-history">
                        <?php foreach ($datos['historial'] as $movimiento): ?>
                            <li>
                                <span class="status <?= $clasesEstado[$movimiento['estado']] ?? 'received' ?>"><?= $escapar($movimiento['estado']) ?></span>
                                <span><?= $escapar($movimiento['usuario']) ?></span>
                                <time datetime="<?= $escapar(str_replace(' ', 'T', $movimiento['fecha_cambio'])) ?>"><?= $escapar((new DateTimeImmutable($movimiento['fecha_cambio']))->format('d/m/Y H:i')) ?></time>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<?php require __DIR__ . '/../partials/pdf-preview.php'; ?>
