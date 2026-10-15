<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocuFlow | Gestión de documentos</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="app-layout">
        <?php require __DIR__ . '/../partials/sidebar.php'; ?>

        <div class="app-content">
            <?php require __DIR__ . '/../partials/header.php'; ?>

            <main id="documentos">
                <?php require $vista; ?>
            </main>
        </div>
    </div>

    <script src="assets/js/main.js"></script>

</body>

</html>
