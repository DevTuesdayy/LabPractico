<?php
$asset = static function (string $ruta): string {
    $archivo = dirname(__DIR__, 3) . '/public/' . $ruta;
    $version = substr(hash_file('sha256', $archivo), 0, 12);

    return htmlspecialchars($ruta . '?v=' . $version, ENT_QUOTES, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $asset('assets/css/styles.css') ?>">
    <?php foreach ($estilos as $estilo): ?>
        <link rel="stylesheet" href="<?= $asset('assets/css/' . $estilo) ?>">
    <?php endforeach; ?>
    <?php if ($cargarScripts): ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="<?= $asset('assets/js/main.js') ?>" defer></script>
    <?php endif; ?>
</head>

<body class="<?= htmlspecialchars($claseBody, ENT_QUOTES, 'UTF-8') ?>">
    <?php require $vista; ?>
</body>

</html>
