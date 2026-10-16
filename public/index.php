<?php

require dirname(__DIR__) . '/app/Controllers/Controller.php';
require dirname(__DIR__) . '/app/Controllers/InicioController.php';
require dirname(__DIR__) . '/app/Controllers/DocumentoController.php';
require dirname(__DIR__) . '/app/Models/Usuario.php';
require dirname(__DIR__) . '/app/Controllers/AuthController.php';
require dirname(__DIR__) . '/app/Models/Documento.php';

session_start([
    'use_strict_mode' => true,
    'use_only_cookies' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
$_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));

$rutas = require dirname(__DIR__) . '/routes/web.php';
$pagina = $_GET['page'] ?? 'documentos';

if (!is_string($pagina) || !isset($rutas[$pagina])) {
    http_response_code(404);
    exit('Página no encontrada.');
}

$ruta = $rutas[$pagina];
$metodoHttp = $_SERVER['REQUEST_METHOD'];

if (!in_array($metodoHttp, $ruta['methods'], true)) {
    http_response_code(405);
    header('Allow: ' . implode(', ', $ruta['methods']));
    exit('Método no permitido.');
}

if ($ruta['auth'] && !isset($_SESSION['usuario'])) {
    if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'mensaje' => 'Debes iniciar sesión.']);
    } else {
        header('Location: index.php?page=login', true, 302);
    }
    exit;
}

$clase = $ruta['controller'];
$metodo = $ruta['action'];

$esLogin = $clase === AuthController::class
    && $metodoHttp === 'POST';

$esDocumento = $clase === DocumentoController::class;

try {
    if ($esLogin || $esDocumento) {
        require dirname(__DIR__) . '/config/conexion.php';

        if ($esLogin) {
            $controlador = new AuthController(
                new Usuario($conexion)
            );
        } else {
            $controlador = new DocumentoController(
                new Documento($conexion)
            );
        }
    } else {
        $controlador = new $clase();
    }
} catch (PDOException $e) {
    error_log('No se pudo conectar a la base de datos.');

    http_response_code(503);

    if (
        stripos(
            $_SERVER['HTTP_ACCEPT'] ?? '',
            'application/json'
        ) !== false
    ) {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'ok' => false,
            'mensaje' => 'Servicio no disponible. Intenta más tarde.'
        ]);
    } else {
        echo 'Servicio no disponible. Intenta más tarde.';
    }

    exit;
}

$controlador->$metodo();
