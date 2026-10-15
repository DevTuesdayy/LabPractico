<?php

require dirname(__DIR__) . '/config/conexion.php';

$consulta = $conexion->query(
    'SELECT
        documentos.no_oficio,
        documentos.asunto,
        documentos.fecha_recepcion,
        estatus.nombre AS estado
    FROM documentos
    INNER JOIN estatus
        ON documentos.id_estatus = estatus.id_estatus
    ORDER BY documentos.fecha_recepcion DESC'
);

$documentos = $consulta->fetchAll();

if (empty($documentos)) {
    echo 'La consulta funciona, pero todavía no hay documentos registrados.';
} else {
    echo '<pre>';
    echo htmlspecialchars(
        print_r($documentos, true),
        ENT_QUOTES,
        'UTF-8'
    );
    echo '</pre>';
}