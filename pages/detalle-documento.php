<!DOCTYPE html>
<!-- detalle-documento.php: ficha frontend con información, archivo y seguimiento del oficio. -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del oficio | DocuFlow</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/ui-refresh.css">
    <link rel="stylesheet" href="../assets/css/ui-interactions.css">
    <link rel="stylesheet" href="../assets/css/detail.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/main.js" defer></script>
</head>

<body class="dashboard-page detail-page">
    <div class="app-shell">
        <?php include __DIR__ . '/../components/sidebar.php'; ?>
        <div class="app-main">
            <?php include __DIR__ . '/../components/header.php'; ?>
            <main class="detail-content">
                <a class="back-link" href="documentos.php">← Volver a la bandeja</a>
                <div class="detail-heading">
                    <div><span class="detail-kicker">OFICIO · REGISTRO 001</span>
                        <h1>Solicitud de material para laboratorios de ciencias</h1>
                        <p>Detalle, trazabilidad y evidencia del documento seleccionado.</p>
                    </div><span class="status received"><i></i>Recibido</span>
                </div>
                <div class="detail-layout">
                    <section class="detail-card detail-main-card">
                        <div class="detail-card-title">
                            <h2>Información del oficio</h2><button class="detail-action">⋮</button>
                        </div>
                        <div class="detail-fields">
                            <div><small>NÚMERO DE OFICIO</small><strong>OF-001/2026</strong></div>
                            <div><small>FECHA DE RECEPCIÓN</small><strong>05 / 10 / 2026</strong></div>
                            <div><small>ÁREA DESTINO</small><strong>Coordinación Académica</strong></div>
                            <div><small>PRIORIDAD</small><span class="priority normal">Normal</span></div>
                            <div class="wide"><small>ASUNTO Y DESCRIPCIÓN</small>
                                <p>Solicitud formal de material para laboratorios de ciencias, consumibles e insumos
                                    para el periodo académico vigente.</p>
                            </div>
                        </div>
                    </section>
                    <aside class="detail-card detail-side-card">
                        <div class="detail-card-title">
                            <h2>Documento adjunto</h2><span class="pdf-badge">PDF</span>
                        </div>
                        <div class="detail-file"><strong>Oficio_001_2026.pdf</strong><small>1.2 MB · 3 páginas</small>
                        </div><button class="detail-download">⇩ Descargar documento</button>
                    </aside>
                </div>
                <section class="detail-card timeline-card">
                    <div class="detail-card-title">
                        <h2>Seguimiento del oficio</h2><span class="timeline-count">3 movimientos</span>
                    </div>
                    <div class="timeline">
                        <div class="timeline-item active"><span></span>
                            <div><strong>Documento recibido</strong><small>05 Oct 2026 · 09:20 hrs · Oficialía de
                                    Partes</small></div>
                        </div>
                        <div class="timeline-item"><span></span>
                            <div><strong>Turnado a Coordinación Académica</strong><small>05 Oct 2026 · 10:05 hrs · Lic.
                                    Carlos Mendoza</small></div>
                        </div>
                        <div class="timeline-item muted"><span></span>
                            <div><strong>Pendiente de atención</strong><small>Fecha límite: 15 Oct 2026</small></div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>

</html>
