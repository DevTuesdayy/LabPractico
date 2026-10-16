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
                    <p class="eyebrow">CONFIRMACIÓN</p>
                    <h1>Eliminar documento</h1>
                    <p class="muted">Revisa el oficio antes de confirmar.</p>
                </div>
            </div>
            <form class="panel document-form" action="index.php?page=eliminar-documento&amp;id=<?= $id ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= $escapar($_SESSION['csrf_token']) ?>">
                <h2>Oficio <?= $escapar($documento['no_oficio']) ?></h2>
                <p class="detail-subject"><?= $escapar($documento['asunto']) ?></p>
                <?php if ($datos['error'] !== null): ?>
                    <div class="form-feedback error" role="alert"><?= $escapar($datos['error']) ?></div>
                <?php endif; ?>
                <p class="form-feedback error">Se eliminarán el registro, su historial de estados y el PDF adjunto. Esta acción no se puede deshacer.</p>
                <div class="form-actions">
                    <a class="button button-secondary" href="index.php?page=detalle-documento&amp;id=<?= $id ?>">Cancelar</a>
                    <button type="submit" class="button button-danger">Sí, eliminar documento</button>
                </div>
            </form>
        </main>
    </div>
</div>
