<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php foreach ($estilos as $estilo): ?>
        <link rel="stylesheet" href="assets/css/<?= htmlspecialchars($estilo, ENT_QUOTES, 'UTF-8') ?>">
    <?php endforeach; ?>
    <?php if ($cargarScripts): ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="assets/js/main.js" defer></script>
    <?php endif; ?>
</head>

<body class="<?= htmlspecialchars($claseBody, ENT_QUOTES, 'UTF-8') ?>">
    <?php require $vista; ?>
</body>

</html>
