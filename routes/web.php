<?php

return [
    'inicio' => [InicioController::class, 'index'],
    'documentos' => [DocumentoController::class, 'index'],
    'registrar-documento' => [DocumentoController::class, 'create'],
    'detalle-documento' => [DocumentoController::class, 'show'],
];
