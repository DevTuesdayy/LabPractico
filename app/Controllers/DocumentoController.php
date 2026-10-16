<?php

class DocumentoController extends Controller{

    private Documento $documentoModel;

    public function __construct(Documento $documentoModel){
        $this->documentoModel = $documentoModel;
    }

    public function index(): void{

        $documentos = $this->documentoModel->listar();
        $mensaje = $_SESSION['listado_mensaje'] ?? null;
        unset($_SESSION['listado_mensaje']);

        $resumen = [
            'total' => count($documentos),
            'recibidos' => 0,
            'en_proceso' => 0,
            'atendidos' => 0
        ];

        foreach($documentos as $documento){
            switch($documento['estado']){
                case 'Recibido':
                    $resumen['recibidos']++;
                    break;
                case 'En proceso':
                    $resumen['en_proceso']++;
                    break;
                case 'Atendido':
                    $resumen['atendidos']++;
                    break;
            }
        }

        $this->render(
            'documentos/index',
            'Registro de Oficios | DocuFlow',
            'dashboard-page',
            [],
            true,
            ['documentos' => $documentos, 'resumen' => $resumen, 'mensaje' => $mensaje]
        );
    }

    public function create(): void
    {
        $this->render(
            'documentos/create',
            'Registrar documento | DocuFlow',
            'register-page',
            []
        );
    }

    public function show(): void
    {
        $idDocumento = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if (!$idDocumento) {
            http_response_code(400);
            echo 'El identificador del documento no es válido.';
            return;
        }

        $documento = $this->documentoModel->buscarPorId($idDocumento);

        if ($documento === null) {
            http_response_code(404);
            echo 'El documento no existe.';
            return;
        }

        $rutaPdf = $this->rutaPdf($documento);
        $mensajeDocumento = $_SESSION['documento_mensaje'][$idDocumento] ?? null;
        unset($_SESSION['documento_mensaje'][$idDocumento]);

        $this->render(
            'documentos/show',
            'Detalle del oficio | DocuFlow',
            'dashboard-page detail-page',
            [],
            true,
            [
                'documento' => $documento,
                'archivoDisponible' => $rutaPdf !== null,
                'archivoTamano' => $rutaPdf !== null ? filesize($rutaPdf) : 0,
                'estatus' => $this->documentoModel->obtenerEstatus(),
                'historial' => $this->documentoModel->obtenerHistorial($idDocumento),
                'mensajeDocumento' => $mensajeDocumento,
            ]
        );
    }

    public function edit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->validarFormulario()) {
            return;
        }

        $documento = $this->documentoSolicitado();
        if ($documento === null) {
            return;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = $this->validarDatos($_POST);
                $this->documentoModel->actualizar((int) $documento['id_documento'], $datos);
                $_SESSION['documento_mensaje'][$documento['id_documento']] = [
                    'tipo' => 'success', 'mensaje' => 'Datos del documento actualizados correctamente.',
                ];
                header('Location: index.php?page=detalle-documento&id=' . $documento['id_documento'], true, 303);
                return;
            } catch (InvalidArgumentException $e) {
                http_response_code(422);
                $error = $e->getMessage();
            } catch (PDOException $e) {
                if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                    http_response_code(409);
                    $error = 'Ya existe un documento con ese número de oficio.';
                } else {
                    error_log('No se pudieron actualizar los datos del documento.');
                    http_response_code(500);
                    $error = 'No se pudo guardar el cambio. Intenta nuevamente.';
                }
            }

            // Conservar lo escrito si hay errores, sin aceptar campos ajenos al formulario.
            foreach (['no_oficio', 'fecha_emision', 'fecha_recepcion', 'asunto', 'remitente', 'destinatario'] as $campo) {
                $documento[$campo] = is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
            }
        }

        $this->render('documentos/edit', 'Editar documento | DocuFlow', 'dashboard-page', [], true, [
            'documento' => $documento, 'error' => $error,
        ]);
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$this->validarFormulario()) {
            return;
        }

        $documento = $this->documentoSolicitado();
        if ($documento === null) {
            return;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rutaPdf = $this->rutaPdf($documento);

            try {
                if (!$this->documentoModel->eliminar((int) $documento['id_documento'])) {
                    http_response_code(404);
                    echo 'El documento ya no existe.';
                    return;
                }
            } catch (PDOException $e) {
                error_log('No se pudo eliminar el documento.');
                http_response_code(500);
                $error = 'No se pudo eliminar el documento. Intenta nuevamente.';
            }

            if ($error === null) {
                $mensaje = ['tipo' => 'success', 'mensaje' => 'Documento eliminado correctamente.'];
                if ($rutaPdf !== null && !unlink($rutaPdf)) {
                    error_log('Se eliminó el registro, pero no se pudo retirar su PDF.');
                    $mensaje = ['tipo' => 'error', 'mensaje' => 'El registro se eliminó, pero no se pudo retirar su PDF del servidor. Revisa los permisos de almacenamiento.'];
                }
                unset($_SESSION['documento_mensaje'][$documento['id_documento']]);
                $_SESSION['listado_mensaje'] = $mensaje;
                header('Location: index.php?page=documentos', true, 303);
                return;
            }
        }

        $this->render('documentos/delete', 'Eliminar documento | DocuFlow', 'dashboard-page', [], true, [
            'documento' => $documento, 'error' => $error,
        ]);
    }

    private function documentoSolicitado(): ?array
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            http_response_code(400);
            echo 'El identificador del documento no es válido.';
            return null;
        }

        $documento = $this->documentoModel->buscarPorId($id);
        if ($documento === null) {
            http_response_code(404);
            echo 'El documento no existe.';
        }
        return $documento;
    }

    private function validarFormulario(): bool
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || $token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            echo 'Formulario inválido. Recarga la página e intenta nuevamente.';
            return false;
        }
        return true;
    }

    public function updateStatus(): void
    {
        $token = $_POST['csrf_token'] ?? '';

        if (!is_string($token) || $token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            echo 'Formulario inválido. Recarga el detalle e intenta nuevamente.';
            return;
        }

        $opciones = ['options' => ['min_range' => 1]];
        $idDocumento = filter_input(INPUT_POST, 'id_documento', FILTER_VALIDATE_INT, $opciones);
        $idEstatus = filter_input(INPUT_POST, 'id_estatus', FILTER_VALIDATE_INT, $opciones);

        if (!$idDocumento) {
            http_response_code(400);
            echo 'El identificador del documento no es válido.';
            return;
        }

        try {
            if (!$idEstatus) {
                throw new InvalidArgumentException('Selecciona un estado válido.');
            }

            $cambio = $this->documentoModel->cambiarEstatus(
                $idDocumento,
                $idEstatus,
                (int) $_SESSION['usuario']['id_usuario']
            );

            $mensaje = $cambio ? 'Estado actualizado correctamente.' : 'El documento ya tiene ese estado.';
            $tipo = 'success';
        } catch (OutOfBoundsException $e) {
            http_response_code(404);
            echo 'El documento no existe.';
            return;
        } catch (InvalidArgumentException $e) {
            $mensaje = $e->getMessage();
            $tipo = 'error';
        } catch (PDOException $e) {
            error_log('No se pudo actualizar el estado del documento.');
            $mensaje = 'No se pudo guardar el cambio. Intenta nuevamente.';
            $tipo = 'error';
        }

        $_SESSION['documento_mensaje'][$idDocumento] = ['tipo' => $tipo, 'mensaje' => $mensaje];
        header('Location: index.php?page=detalle-documento&id=' . $idDocumento, true, 303);
    }

    public function download(): void
    {
        $this->enviarPdf(true);
    }

    public function preview(): void
    {
        $this->enviarPdf(false);
    }

    private function enviarPdf(bool $descargar): void
    {
        $idDocumento = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if (!$idDocumento) {
            http_response_code(400);
            echo 'El identificador del documento no es válido.';
            return;
        }

        $documento = $this->documentoModel->buscarPorId($idDocumento);
        $rutaPdf = $documento !== null ? $this->rutaPdf($documento) : null;

        if ($rutaPdf === null) {
            http_response_code(404);
            echo 'El documento o su archivo PDF no están disponibles.';
            return;
        }

        $archivo = fopen($rutaPdf, 'rb');

        if ($archivo === false) {
            http_response_code(404);
            echo 'El archivo PDF no está disponible.';
            return;
        }

        session_write_close();
        header('Content-Type: application/pdf');
        $disposicion = $descargar ? 'attachment' : 'inline';
        header('Content-Disposition: ' . $disposicion . '; filename="oficio-' . $idDocumento . '.pdf"');
        header('Content-Length: ' . fstat($archivo)['size']);
        fpassthru($archivo);
        fclose($archivo);
    }

    private function rutaPdf(array $documento): ?string
    {
        $storage = dirname(__DIR__, 2) . '/storage';
        $carpeta = realpath($storage . '/documentos');
        $ruta = realpath($storage . '/' . $documento['archivo_pdf']);

        if (
            $carpeta === false || $ruta === false ||
            !str_starts_with($ruta, $carpeta . DIRECTORY_SEPARATOR) ||
            !is_file($ruta) || !is_readable($ruta) ||
            strtolower(pathinfo($ruta, PATHINFO_EXTENSION)) !== 'pdf'
        ) {
            return null;
        }

        return $ruta;
    }

    public function store(): void
    {
        if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 27 * 1024 * 1024) {
            $this->responderRegistro(413, 'El PDF debe pesar como máximo 25 MB.');
            return;
        }

        $token = $_POST['csrf_token'] ?? '';

        if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token) || $token === '') {
            $this->responderRegistro(403, 'Formulario inválido. Recarga la página.');
            return;
        }

        $archivoGuardado = null;
        $registroCompleto = false;

        try {
            $datos = $this->validarDatos($_POST);

            $archivo = $_FILES['archivo_pdf'] ?? [];

            if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                throw new InvalidArgumentException('Selecciona un PDF de hasta 25 MB y comprueba que termine de cargar.');
            }

            $temporal = $archivo['tmp_name'];

            if (!is_uploaded_file($temporal)) {
                throw new InvalidArgumentException('El archivo recibido no es válido.');
            }

            $tamano = filesize($temporal);

            if (!$tamano || $tamano > 25 * 1024 * 1024) {
                throw new InvalidArgumentException('El PDF debe tener contenido y pesar como máximo 25 MB.');
            }

            if ((new finfo(FILEINFO_MIME_TYPE))->file($temporal) !== 'application/pdf') {
                throw new InvalidArgumentException('Solo se permiten archivos PDF.');
            }

            $carpeta = dirname(__DIR__, 2) . '/storage/documentos';
            $nombre = bin2hex(random_bytes(16)) . '.pdf';

            if (!is_dir($carpeta) || !is_writable($carpeta)) {
                throw new RuntimeException('La carpeta de documentos no permite escritura.');
            }

            if (!move_uploaded_file($temporal, $carpeta . '/' . $nombre)) {
                throw new RuntimeException('No se pudo guardar el PDF.');
            }

            $archivoGuardado = $carpeta . '/' . $nombre;
            $datos['archivo_pdf'] = 'documentos/' . $nombre;
            $idUsuario = (int) $_SESSION['usuario']['id_usuario'];
            $idDocumento = $this->documentoModel->registrar($datos, $idUsuario);
            $registroCompleto = true;

            $this->responderRegistro(201, 'Documento registrado correctamente.', $idDocumento);
        } catch (InvalidArgumentException $e) {
            $this->responderRegistro(422, $e->getMessage());
        } catch (PDOException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                $this->responderRegistro(409, 'Ya existe un documento con ese número de oficio.');
            } else {
                error_log('Error de base de datos al registrar un documento.');
                $this->responderRegistro(500, 'No se pudo registrar el documento.');
            }
        } catch (Throwable $e) {
            error_log('Error al registrar un documento: ' . $e->getMessage());
            $this->responderRegistro(500, 'No se pudo guardar el documento. Revisa la configuración del servidor.');
        } finally {
            if (!$registroCompleto && $archivoGuardado !== null && !unlink($archivoGuardado)) {
                error_log('No se pudo retirar el PDF de un registro fallido.');
            }
        }
    }

    private function validarDatos(array $entrada): array
    {
        $datos = [];

        foreach (['no_oficio' => 50, 'asunto' => 255, 'remitente' => 150, 'destinatario' => 150] as $campo => $limite) {
            $valor = $entrada[$campo] ?? '';

            if (!is_string($valor) || trim($valor) === '' || mb_strlen(trim($valor)) > $limite) {
                throw new InvalidArgumentException("Revisa $campo: es obligatorio y admite hasta $limite caracteres.");
            }

            $datos[$campo] = trim($valor);
        }

        foreach (['fecha_emision', 'fecha_recepcion'] as $campo) {
            $valor = $entrada[$campo] ?? '';
            $fecha = is_string($valor) ? DateTimeImmutable::createFromFormat('!Y-m-d', $valor) : false;

            if (!$fecha || $fecha->format('Y-m-d') !== $valor) {
                throw new InvalidArgumentException("Revisa $campo: debe ser una fecha válida.");
            }

            $datos[$campo] = $valor;
        }

        return $datos;
    }

    private function responderRegistro(int $estado, string $mensaje, ?int $idDocumento = null): void
    {
        http_response_code($estado);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok' => $estado === 201,
            'mensaje' => $mensaje,
            'id_documento' => $idDocumento,
        ], JSON_UNESCAPED_UNICODE);
    }
}
