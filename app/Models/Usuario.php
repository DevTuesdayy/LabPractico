<?php

class Usuario{
    private PDO $conexion;

    public function __construct(PDO $conexion){
        $this->conexion = $conexion;
    }

    public function buscarPorEmail(string $email): ?array{
        $consulta = $this->conexion->prepare(
            'Select id_usuario, nombre, email, password FROM usuarios where email = :email limit 1'
        );

        $consulta->execute([
            'email' => $email
        ]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        return $usuario === false ? null : $usuario;
    }
}