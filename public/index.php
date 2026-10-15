<?php

require dirname(__DIR__) . '/app/Controllers/Controller.php';
require dirname(__DIR__) . '/app/Controllers/InicioController.php';
require dirname(__DIR__) . '/app/Controllers/DocumentoController.php';

$rutas = require dirname(__DIR__) . '/routes/web.php';
$pagina = $_GET['page'] ?? 'documentos';

if (!is_string($pagina) || !isset($rutas[$pagina])) {
    http_response_code(404);
    exit('Página no encontrada.');
}

[$clase, $metodo] = $rutas[$pagina];
$controlador = new $clase();
$controlador->$metodo();
