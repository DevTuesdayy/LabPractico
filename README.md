# LabPractico — DocuFlow

Aplicación de gestión documental en PHP y MySQL. La estructura prepara MVC sin
framework. El registro y el detalle conservan sus vistas vacías; todavía no hay
autenticación ni operaciones de documentos en la base desde el controlador.

## Estructura

```text
app/
  Controllers/DocumentoController.php
  Models/                         # Consultas y acceso a datos
  Views/
    documentos/                   # index.php, create.php, show.php
    layouts/main.php              # Plantilla de la página
    partials/                     # header.php y sidebar.php
config/
  conexion.php                    # Credenciales locales; ignorado por Git
  conexion.example.php            # Ejemplo sin credenciales reales
database/
  gestion_documentos_simple.sql
public/
  index.php                       # Entrada de las peticiones web
  assets/                         # CSS y JavaScript
routes/web.php                    # Rutas permitidas
scripts/                          # Pruebas manuales desde la terminal
index.php                         # Redirección temporal a public/index.php
```

## Recorrido de una petición

`public/index.php` consulta `routes/web.php`, llama a `DocumentoController` y este
carga una vista dentro de `main.php`. Los modelos se añadirán al implementar el
acceso a datos. Las vistas mantienen el HTML del frontend existente.

Rutas actuales:

- `index.php`: bandeja de documentos.
- `index.php?page=registrar-documento`: vista de registro.
- `index.php?page=detalle-documento`: vista de detalle.

Para el login se añadirán `app/Controllers/AuthController.php`,
`app/Models/Usuario.php` y `app/Views/auth/login.php`. El formulario enviará la
petición a una ruta de la entrada pública; no se accederá al controlador por URL.

## Configuración local

La conexión existente permanece en `config/conexion.php`. En una instalación
nueva, copiar `config/conexion.example.php` a `config/conexion.php` y completar los
datos de MySQL. No subir las credenciales a Git.

En `/opt/homebrew/etc/httpd/extra/httpd-vhosts.conf`, el bloque de `LabPractico`
debe usar:

```apache
DocumentRoot "/opt/homebrew/var/www/pracPHP/LabPractico/public"

<Directory "/opt/homebrew/var/www/pracPHP/LabPractico/public">
    AllowOverride All
    Require all granted
</Directory>
```

Después de comprobar la configuración y reiniciar Apache, la URL seguirá siendo
`http://LabPractico`. Mientras Apache apunte a la raíz anterior, el `index.php`
temporal redirige a `public/index.php`. Los `.htaccess` de las carpetas internas
deniegan su acceso web con la configuración actual de `AllowOverride All`.

## Consultas manuales

Desde la raíz del proyecto:

```bash
php scripts/probar_conexion.php
php scripts/probar_documentos.php
```

Estos scripts conservan las pruebas existentes y ya no se sirven por URL.
El SQL conserva su contenido original: ejecutarlo completo elimina y recrea
la base; no usarlo para actualizar una base con registros.
