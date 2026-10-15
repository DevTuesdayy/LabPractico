<?php

// Copiar a conexion.php y configurar las credenciales locales.
$servidor = '127.0.0.1';
$puerto = '3306';
$baseDatos = 'gestion_documentos';
$usuario = 'TU_USUARIO';
$contrasena = 'TU_CONTRASEÑA';

$conexion = new PDO(
    "mysql:host=$servidor;port=$puerto;dbname=$baseDatos;charset=utf8mb4",
    $usuario,
    $contrasena,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
