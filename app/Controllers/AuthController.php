<?php

class AuthController extends Controller
{
    public function __construct(private ?Usuario $usuarioModel = null)
    {
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_SESSION['usuario'])) {
                header('Location: index.php?page=documentos');
                return;
            }

            $this->render(
                'auth/login',
                'Iniciar sesión | DocuFlow',
                'login-page',
                ['login.css'],
                false
            );
            return;
        }

        if (!$this->tokenValido()) {
            $this->responder(403, 'La sesión del formulario expiró. Intenta nuevamente.');
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (
            !is_string($email) ||
            !is_string($password) ||
            strlen($email) > 120 ||
            !filter_var(trim($email), FILTER_VALIDATE_EMAIL) ||
            $password === '' ||
            strlen($password) > 4096
        ) {
            $this->responder(422, 'Ingresa un correo válido y una contraseña.');
            return;
        }

        try {
            if ($this->usuarioModel === null) {
                throw new LogicException('Falta configurar el modelo de usuarios.');
            }

            $usuario = $this->usuarioModel->buscarPorEmail(trim($email));
        } catch (PDOException | LogicException $e) {
            error_log('No se pudo consultar el usuario al iniciar sesión.');
            $this->responder(503, 'Servicio no disponible. Intenta más tarde.');
            return;
        }

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $this->responder(401, 'Correo o contraseña incorrectos.');
            return;
        }

        session_regenerate_id(true);
        $_SESSION = [
            'usuario' => [
                'id_usuario' => (int) $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
            ],
            'csrf_token' => bin2hex(random_bytes(32)),
        ];

        $this->responder(200, 'Sesión iniciada correctamente.');
    }

    public function logout(): void
    {
        if (!$this->tokenValido()) {
            http_response_code(403);
            echo 'No se pudo cerrar la sesión. Recarga la página e intenta nuevamente.';
            return;
        }

        $_SESSION = [];
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 3600,
            'path' => $cookie['path'],
            'domain' => $cookie['domain'],
            'secure' => $cookie['secure'],
            'httponly' => $cookie['httponly'],
            'samesite' => $cookie['samesite'],
        ]);
        session_destroy();

        header('Location: index.php?page=login', true, 303);
    }

    private function tokenValido(): bool
    {
        $token = $_POST['csrf_token'] ?? '';

        return is_string($token)
            && isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    private function responder(int $estado, string $mensaje): void
    {
        $ok = $estado === 200;

        if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
            http_response_code($estado);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => $ok,
                'mensaje' => $mensaje,
                'redirect' => $ok ? 'index.php?page=documentos' : null,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (!$ok) {
            $email = $_POST['email'] ?? '';
            $_SESSION['login_error'] = $mensaje;
            $_SESSION['login_email'] = is_string($email) ? substr(trim($email), 0, 120) : '';
        }

        header('Location: index.php?page=' . ($ok ? 'documentos' : 'login'), true, 303);
    }
}
