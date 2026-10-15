<?php

class AuthController{
    private Usuario $usuarioModel;

    public function __construct(Usuario $usuarioModel){
        $this->usuarioModel = $usuarioModel;
    }

    public function login(): void{
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Allow: POST');

            $this->responder(405, [
                'ok' => false,
                'mensaje' => 'Debes enviar los datos mediante POST'
            ]);
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_Post['password'] ?? '';

        if(
            !is_string($email) ||
            !is_string($password) ||
            !filter_var(trim($email), FILTER_VALIDATE_EMAIL) ||
            $password === ''
        ){
            $this->responder(422, [
                'ok' => false,
                'mensaje' => 'Ingresa un correo valido y una contraseña'
            ]);
            return;
        }

        try{
            $usuario = $this->usuarioModel->buscarPorEmail(trim($email));
        } catch (PDOException $e){
            error_log('Error de base de datos al iniciar sesion');

            $this->responder(500, [
                'ok' => false,
                'mensaje' => 'No se pudo iniciar sesion'
            ]);
            return;
        }

        if (
            !$usuario ||
            !password_verify($password, $usuario['password'])
        ) {
            $this->responder(401, [
                'ok' => false,
                'mensaje' => 'Correo o contraseña incorrectos.'
            ]);
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start([
                'use_strict_mode' => true,
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'cookie_secure' => !empty($_SERVER['HTTPS'])
                    && $_SERVER['HTTPS'] !== 'off'
            ]);
        }

        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id_usuario' => (int) $usuario['id_usuario'],
            'nombre' => $usuario['nombre']
        ];

        $this->responder(200, [
            'ok' => true,
            'mensaje' => 'Sesión iniciada correctamente.'
        ]);
    }

    private function responder(int $estado, array $datos): void{
        http_response_code($estado);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }
}