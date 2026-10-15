<div class="app-shell">
    <?php require __DIR__ . '/../partials/sidebar.php'; ?>
    <div class="app-main">
        <?php require __DIR__ . '/../partials/header.php'; ?>
        <main class="page-content page-content-narrow">
            <a class="back-link" href="index.php?page=documentos">← Volver a documentos</a>
            <div class="page-heading">
                <div>
                    <p class="eyebrow">REGISTRO</p>
                    <h1>Nuevo documento</h1>
                    <p class="muted">Captura los datos del oficio y adjunta su archivo PDF.</p>
                </div>
            </div>

            <form class="panel document-form" id="register-form" action="index.php?page=guardar-documento" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                <h2>Datos del documento</h2>
                <p class="muted form-help">Todos los campos son obligatorios. El estado inicial será Recibido.</p>
                <div class="form-grid form-grid-three">
                    <div class="field">
                        <label for="no_oficio">Número de oficio</label>
                        <input type="text" id="no_oficio" name="no_oficio" maxlength="50" placeholder="Ej. OF-0452/2026" required>
                    </div>
                    <div class="field">
                        <label for="fecha_emision">Fecha de emisión</label>
                        <input type="date" id="fecha_emision" name="fecha_emision" required>
                    </div>
                    <div class="field">
                        <label for="fecha_recepcion">Fecha de recepción</label>
                        <input type="date" id="fecha_recepcion" name="fecha_recepcion" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="remitente">Remitente</label>
                        <input type="text" id="remitente" name="remitente" maxlength="150" placeholder="Persona o institución que lo envía" required>
                    </div>
                    <div class="field">
                        <label for="destinatario">Destinatario o área responsable</label>
                        <input type="text" id="destinatario" name="destinatario" maxlength="150" placeholder="Persona o área que lo recibe" required>
                    </div>
                </div>

                <div class="field">
                    <label for="asunto">Asunto</label>
                    <textarea id="asunto" name="asunto" maxlength="255" rows="4" placeholder="Describe brevemente el asunto" aria-describedby="asunto-ayuda" required></textarea>
                    <small class="muted" id="asunto-ayuda">Máximo 255 caracteres.</small>
                </div>

                <div class="upload-box">
                    <label for="archivo_pdf">Archivo PDF</label>
                    <p class="muted" id="archivo-ayuda">Selecciona un PDF o arrástralo aquí. Máximo 25 MB.</p>
                    <input type="file" id="archivo_pdf" name="archivo_pdf" accept=".pdf,application/pdf" aria-describedby="archivo-ayuda archivo-info" required>
                    <small class="file-info" id="archivo-info" aria-live="polite">Ningún archivo seleccionado.</small>
                </div>

                <div id="register-feedback" class="form-feedback" role="status" aria-live="polite" hidden></div>
                <div class="form-actions">
                    <a class="button button-secondary" href="index.php?page=documentos">Cancelar</a>
                    <button type="submit" class="button">Guardar documento</button>
                </div>
            </form>
        </main>
    </div>
</div>
