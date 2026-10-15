DROP DATABASE IF EXISTS gestion_documentos;
CREATE DATABASE gestion_documentos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_documentos;

CREATE TABLE estatus (
    id_estatus INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(100) NOT NULL,
    email      VARCHAR(120) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL
);

CREATE TABLE documentos (
    id_documento INT AUTO_INCREMENT PRIMARY KEY,
    no_oficio    VARCHAR(50)  NOT NULL UNIQUE,
    fecha_emision DATE        NOT NULL,
    fecha_recepcion DATE      NOT NULL,
    asunto       VARCHAR(255) NOT NULL,
    remitente    VARCHAR(150) NOT NULL,
    destinatario VARCHAR(150) NOT NULL,
    id_estatus   INT          NOT NULL DEFAULT 1,
    archivo_pdf  VARCHAR(255) NOT NULL,
    id_usuario   INT          NOT NULL,
    CONSTRAINT fk_doc_estatus FOREIGN KEY (id_estatus) REFERENCES estatus(id_estatus),
    CONSTRAINT fk_doc_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE historial_estatus (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_documento INT      NOT NULL,
    id_estatus   INT      NOT NULL,
    id_usuario   INT      NOT NULL,
    fecha_cambio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_hist_documento FOREIGN KEY (id_documento) REFERENCES documentos(id_documento) ON DELETE CASCADE,
    CONSTRAINT fk_hist_estatus   FOREIGN KEY (id_estatus)   REFERENCES estatus(id_estatus),
    CONSTRAINT fk_hist_usuario   FOREIGN KEY (id_usuario)   REFERENCES usuarios(id_usuario)
);

INSERT INTO estatus (nombre) VALUES ('Recibido'), ('En proceso'), ('Atendido');
