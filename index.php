<?php

$consulta = http_build_query($_GET);
header('Location: public/index.php' . ($consulta === '' ? '' : '?' . $consulta));
exit;
