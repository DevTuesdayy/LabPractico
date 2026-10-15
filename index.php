<!DOCTYPE html>
<!-- index.php: pantalla de bienvenida y acceso a los módulos principales. -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocuFlow | Gestión Documental</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/ui-refresh.css">
</head>

<body class="home-page">
    <header class="home-header">
        <a class="home-brand" href="index.php"><span
                class="home-brand-icon">✦</span><span><strong>DocuFlow</strong><small>GESTIÓN
                    DOCUMENTAL</small></span></a>
        <span class="home-status"><i></i> Sistema operativo</span>
    </header>
    <main class="home-content">
        <section class="home-hero">
            <div class="home-hero-copy">
                <span class="home-badge">CENTRO DE OPERACIONES</span>
                <h1>Todo tu flujo documental, <em>en un solo lugar.</em></h1>
                <p>Gestiona oficios, digitaliza evidencias y da seguimiento a cada documento con una experiencia clara y
                    ordenada.</p>
                <div class="home-actions"><a href="pages/documentos.php" class="home-cta primary-link">Abrir panel
                        <span>→</span></a><a href="pages/registrar-documento.php"
                        class="home-cta secondary-link">Registrar oficio <span>＋</span></a></div>
            </div>
            <div class="home-visual">
                <div class="visual-glow"></div>
                <div class="visual-card"><span class="visual-label">ACTIVIDAD DE
                        HOY</span><strong>148</strong><small>oficios registrados</small>
                    <div class="visual-chart"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="visual-foot"><span><b></b> +14 hoy</span><span>Panel activo</span></div>
                </div>
            </div>
        </section>
        <section class="home-links"><a href="pages/documentos.php" class="home-link"><span
                    class="home-link-icon">▤</span><span><strong>Bandeja de documentos</strong><small>Consulta, filtra y
                        organiza los oficios registrados</small></span><b>→</b></a><a
                href="pages/registrar-documento.php" class="home-link"><span
                    class="home-link-icon purple">＋</span><span><strong>Registrar documento</strong><small>Captura un
                        nuevo oficio y digitaliza su evidencia</small></span><b>→</b></a>
            <div class="home-mini-stats">
                <span><strong>24/7</strong><small>Disponibilidad</small></span><span><strong>99.2%</strong><small>Legibilidad
                        OCR</small></span><span><strong>100%</strong><small>Trazabilidad</small></span>
            </div>
        </section>
    </main>
    <footer class="home-footer">DocuFlow <span>•</span> Gestión documental institucional</footer>
</body>

</html>