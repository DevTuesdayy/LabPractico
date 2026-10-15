<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Solo disponible desde la terminal.');
}

function leerDato(string $etiqueta): string
{
    fwrite(STDOUT, $etiqueta);
    return trim(fgets(STDIN) ?: '');
}

$nombre = leerDato('Nombre: ');
$email = leerDato('Correo: ');
$terminal = function_exists('stream_isatty') && stream_isatty(STDIN);

if ($terminal) {
    system('stty -echo');
}

try {
    fwrite(STDOUT, 'Contraseña (mínimo 12 caracteres): ');
    $password = rtrim(fgets(STDIN) ?: '', "\r\n");
} finally {
    if ($terminal) {
        system('stty echo');
    }
    fwrite(STDOUT, PHP_EOL);
}

if (
    $nombre === '' || mb_strlen($nombre) > 100 ||
    strlen($email) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($password) < 12 || strlen($password) > 72
) {
    fwrite(STDERR, "Revisa el nombre, correo y contraseña (12 a 72 bytes).\n");
    exit(1);
}

try {
    require dirname(__DIR__) . '/config/conexion.php';
    $consulta = $conexion->prepare(
        'INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)'
    );
    $consulta->execute([
        'nombre' => $nombre,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    ]);
    echo "Cuenta creada correctamente.\n";
} catch (PDOException $e) {
    fwrite(STDERR, $e->getCode() === '23000'
        ? "Ese correo ya está registrado.\n"
        : "No se pudo crear la cuenta. Revisa MySQL y la configuración.\n");
    exit(1);
}
