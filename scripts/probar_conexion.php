<?php

require dirname(__DIR__) . '/config/conexion.php';

$consulta = $conexion->query('Select nombre from estatus');
$estatus = $consulta->fetchAll();

echo 'Conexion exitosa<br>';

foreach ($estatus as $fila) {
    echo htmlspecialchars($fila['nombre'], ENT_QUOTES, 'UTF-8') . '<br>';
}
