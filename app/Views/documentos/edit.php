<?php
$documento = $datos['documento'];
$escapar = fn($valor) => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$id = (int) $documento['id_documento'];
?>
<div class="app-shell">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="app-main">
        <?php require __DIR__ . '/../partials/header.php'; ?>
        <main class="page-content page-content-narrow">
            <a class="back-link" href="index.php?page=detalle-documento&amp;id=<?= $id ?>">← Volver al detalle</a>
            <div class="page-heading">
                <div>
                    <p class="eyebrow">DOCUMENTOS</p>
                    <h1>Editar documento</h1>
                    <p class="muted">Corrige los datos del oficio. El PDF y el estado actual se conservan.</p>
                </div>
            </div>

            <form class="panel document-form" action="index.php?page=editar-documento&amp;id=<?= $id ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= $escapar($_SESSION['csrf_token']) ?>">
                <h2>Datos del documento</h2>
                <?php if ($datos['error'] !== null): ?>
                    <div class="form-feedback error" role="alert"><?= $escapar($datos['error']) ?></div>
                <?php endif; ?>

                <div class="form-grid form-grid-three">
                    <div class="field">
                        <label for="no_oficio">Número de oficio</label>
                        <input type="text" id="no_oficio" name="no_oficio" maxlength="50" value="<?= $escapar($documento['no_oficio']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="fecha_emision">Fecha de emisión</label>
                        <input type="date" id="fecha_emision" name="fecha_emision" value="<?= $escapar($documento['fecha_emision']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="fecha_recepcion">Fecha de recepción</label>
                        <input type="date" id="fecha_recepcion" name="fecha_recepcion" value="<?= $escapar($documento['fecha_recepcion']) ?>" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label for="remitente">Remitente</label>
                        <input type="text" id="remitente" name="remitente" maxlength="150" value="<?= $escapar($documento['remitente']) ?>" required>
                    </div>
                    <div class="field">
                        <label for="destinatario">Destinatario o área responsable</label>
                        <input type="text" id="destinatario" name="destinatario" maxlength="150" value="<?= $escapar($documento['destinatario']) ?>" required>
                    </div>
                </div>
                <div class="field">
                    <label for="asunto">Asunto</label>
                    <textarea id="asunto" name="asunto" maxlength="255" rows="4" required><?= $escapar($documento['asunto']) ?></textarea>
                </div>
                <div class="form-actions">
                    <a class="button button-secondary" href="index.php?page=detalle-documento&amp;id=<?= $id ?>">Cancelar</a>
                    <button type="submit" class="button">Guardar cambios</button>
                </div>
            </form>
        </main>
    </div>
</div>
