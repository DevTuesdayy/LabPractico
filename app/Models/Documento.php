<?php

class Documento{
    private PDO $conexion;

    public function __construct(PDO $conexion){
        $this->conexion = $conexion;
    }

    public function obtenerEstatus(): array{
        $consulta = $this->conexion->query(
            'SELECT id_estatus, nombre FROM estatus ORDER BY id_estatus'
        );

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar(array $datos, int $idUsuario): int{
        $this->conexion->beginTransaction();

        try{
            $consultaEstatus = $this->conexion->prepare(
                'SELECT id_estatus FROM estatus WHERE nombre = :nombre'
            );

            $consultaEstatus->execute([
                'nombre' => 'Recibido'
            ]);

            $idEstatus = $consultaEstatus->fetchColumn();

            if($idEstatus === false){
                throw new RuntimeException(
                    'No existe el estatus recibido'
                );
            }

            $consulta = $this->conexion->prepare(
                'INSERT INTO documentos (
                    no_oficio,
                    fecha_emision,
                    fecha_recepcion,
                    asunto,
                    remitente,
                    destinatario,
                    id_estatus,
                    archivo_pdf,
                    id_usuario
                ) VALUES (
                    :no_oficio,
                    :fecha_emision,
                    :fecha_recepcion,
                    :asunto,
                    :remitente,
                    :destinatario,
                    :id_estatus,
                    :archivo_pdf,
                    :id_usuario
                )'
            );

            $consulta->execute([
                'no_oficio' => $datos['no_oficio'],
                'fecha_emision' => $datos['fecha_emision'],
                'fecha_recepcion' => $datos['fecha_recepcion'],
                'asunto' => $datos['asunto'],
                'remitente' => $datos['remitente'],
                'destinatario' => $datos['destinatario'],
                'id_estatus' => (int) $idEstatus,
                'archivo_pdf' => $datos['archivo_pdf'],
                'id_usuario' => $idUsuario
            ]);

            $idDocumento = (int) $this->conexion->lastInsertId();

            $historial = $this->conexion->prepare(
                'INSERT INTO historial_estatus (
                    id_documento,
                    id_estatus,
                    id_usuario
                ) VALUES (
                    :id_documento,
                    :id_estatus,
                    :id_usuario
                )'
            );

            $historial->execute([
                'id_documento' => $idDocumento,
                'id_estatus' => (int) $idEstatus,
                'id_usuario' => $idUsuario
            ]);

            $this->conexion->commit();

            return $idDocumento;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }    

    public function listar(): array{
        $consulta = $this->conexion->query(
            'SELECT d.id_documento,
            d.no_oficio,
            d.fecha_emision,
            d.fecha_recepcion,
            d.asunto,
            d.remitente,
            d.destinatario,
            e.nombre AS estado,
            u.nombre AS registrado_por
            FROM documentos d
            INNER JOIN estatus e on d.id_estatus = e.id_estatus
            INNER JOIN usuarios u on d.id_usuario = u.id_usuario
            ORDER BY d.fecha_recepcion DESC, d.id_documento DESC'
        );

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerHistorial(int $idDocumento): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT h.fecha_cambio, e.nombre AS estado, u.nombre AS usuario
             FROM historial_estatus h
             INNER JOIN estatus e ON h.id_estatus = e.id_estatus
             INNER JOIN usuarios u ON h.id_usuario = u.id_usuario
             WHERE h.id_documento = :id_documento
             ORDER BY h.fecha_cambio DESC, h.id_historial DESC'
        );
        $consulta->execute(['id_documento' => $idDocumento]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstatus(int $idDocumento, int $idEstatus, int $idUsuario): bool
    {
        $this->conexion->beginTransaction();

        try {
            $consulta = $this->conexion->prepare(
                'SELECT id_estatus FROM documentos WHERE id_documento = :id_documento FOR UPDATE'
            );
            $consulta->execute(['id_documento' => $idDocumento]);
            $estadoActual = $consulta->fetchColumn();

            if ($estadoActual === false) {
                throw new OutOfBoundsException('El documento no existe.');
            }

            $consulta = $this->conexion->prepare('SELECT id_estatus FROM estatus WHERE id_estatus = :id_estatus');
            $consulta->execute(['id_estatus' => $idEstatus]);

            if ($consulta->fetchColumn() === false) {
                throw new InvalidArgumentException('Selecciona un estado válido.');
            }

            // Evitar movimientos repetidos si se envía el mismo estado otra vez.
            if ((int) $estadoActual === $idEstatus) {
                $this->conexion->commit();
                return false;
            }

            $consulta = $this->conexion->prepare(
                'UPDATE documentos SET id_estatus = :id_estatus WHERE id_documento = :id_documento'
            );
            $consulta->execute(['id_estatus' => $idEstatus, 'id_documento' => $idDocumento]);

            $consulta = $this->conexion->prepare(
                'INSERT INTO historial_estatus (id_documento, id_estatus, id_usuario)
                 VALUES (:id_documento, :id_estatus, :id_usuario)'
            );
            $consulta->execute([
                'id_documento' => $idDocumento,
                'id_estatus' => $idEstatus,
                'id_usuario' => $idUsuario,
            ]);

            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $e;
        }
    }

    public function buscarPorId(int $idDocumento): ?array{
        $consulta = $this->conexion->prepare(
            'SELECT d.*,
            e.nombre as estado,
            u.nombre as registrado_por
            from documentos d
            inner join estatus e on d.id_estatus = e.id_estatus
            inner join usuarios u on d.id_usuario = u.id_usuario
            where d.id_documento = :id_documento'
        );

        $consulta->execute([
            'id_documento' => $idDocumento
        ]);

        $documento = $consulta->fetch(PDO::FETCH_ASSOC);

        return $documento === false ? null : $documento;
    }
}
