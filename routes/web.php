<?php

return [
    'login' => [
        'controller' => AuthController::class,
        'action' => 'login',
        'methods' => ['GET', 'POST'],
        'auth' => false,
    ],
    'logout' => [
        'controller' => AuthController::class,
        'action' => 'logout',
        'methods' => ['POST'],
        'auth' => true,
    ],
    'inicio' => [
        'controller' => InicioController::class,
        'action' => 'index',
        'methods' => ['GET'],
        'auth' => true,
    ],
    'documentos' => [
        'controller' => DocumentoController::class,
        'action' => 'index',
        'methods' => ['GET'],
        'auth' => true,
    ],
    'registrar-documento' => [
        'controller' => DocumentoController::class,
        'action' => 'create',
        'methods' => ['GET'],
        'auth' => true,
    ],
    'guardar-documento' => [
        'controller' => DocumentoController::class,
        'action' => 'store',
        'methods' => ['POST'],
        'auth' => true,
    ],
    'detalle-documento' => [
        'controller' => DocumentoController::class,
        'action' => 'show',
        'methods' => ['GET'],
        'auth' => true,
    ],
    'actualizar-estado' => [
        'controller' => DocumentoController::class,
        'action' => 'updateStatus',
        'methods' => ['POST'],
        'auth' => true,
    ],
    'descargar-documento' => [
        'controller' => DocumentoController::class,
        'action' => 'download',
        'methods' => ['GET'],
        'auth' => true,
    ],
];
