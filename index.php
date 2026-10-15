<?php

// Compatibilidad mientras Apache todavía apunta a la raíz del proyecto.
$consulta = http_build_query($_GET);
header('Location: public/index.php' . ($consulta === '' ? '' : '?' . $consulta));
exit;
