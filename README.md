# LabPractico — DocuFlow

Aplicación de gestión documental en PHP y MySQL. La estructura prepara MVC sin
framework. Incluye inicio de sesión, registro de documentos con PDF, bandeja
con datos reales, consulta de detalle, cambio de estado con historial y descarga
del archivo adjunto. El login
usa cuentas de MySQL, contraseñas con hash y sesiones de PHP.

## Estructura

```text
app/
  Controllers/                    # Inicio, documentos y autenticación
  Models/                         # Consultas y acceso a datos
  Views/
    auth/login.php                # Formulario de acceso
    documentos/                   # index.php, create.php, show.php
    home/index.php                # Acceso a documentos y registro
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
scripts/crear_usuario.php         # Crear cuentas desde terminal
storage/documentos/               # PDFs privados, excluidos de Git
index.php                         # Redirección temporal a public/index.php
```

## Recorrido de una petición

`public/index.php` consulta `routes/web.php` y llama al controlador correspondiente.
`InicioController` y `DocumentoController` comparten la carga de vistas y del
layout `main.php` mediante `Controller`. Las vistas mantienen el HTML del
frontend adaptado a las rutas MVC. Los estilos compartidos están en
`public/assets/css/styles.css`; el login conserva `login.css`. Las pantallas
muestran los campos y acciones que tienen funcionamiento implementado.

Rutas actuales:

- `index.php`: bandeja de documentos.
- `index.php?page=inicio`: bienvenida.
- `index.php?page=registrar-documento`: vista de registro.
- `index.php?page=guardar-documento`: POST registra documento y PDF.
- `index.php?page=detalle-documento&id=1`: detalle de un documento existente.
- `index.php?page=actualizar-estado`: POST cambia el estado y registra el movimiento.
- `index.php?page=descargar-documento&id=1`: GET descarga su PDF con sesión iniciada.
- `index.php?page=login`: GET muestra el formulario; POST valida las credenciales.
- `index.php?page=logout`: POST cierra la sesión.

Inicio y todas las páginas de documentos requieren una sesión. El controlador
regenera el identificador al autenticar y destruye la sesión al salir. Login y
logout validan un token CSRF. La contraseña nunca se guarda en la sesión.

El formulario usa POST y redirecciones. Para AJAX, enviar `Accept:
application/json`, los campos `email`, `password` y `csrf_token`, y la cookie de
sesión obtenida al cargar el formulario. Los errores devuelven 401 para
credenciales incorrectas, 403 para token inválido, 422 para datos inválidos y
503 cuando falla MySQL. Las rutas rechazan métodos HTTP no permitidos con 405.

No existe registro público ni gestión web de usuarios. La creación de cuentas
se realiza desde terminal. La tabla actual no distingue roles de administrador
y usuario; la autorización para un futuro panel de cuentas queda pendiente.

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

## Crear una cuenta

Desde la raíz del proyecto:

```bash
php scripts/crear_usuario.php
```

Solicita nombre, correo y contraseña. Oculta la contraseña en terminal y guarda
solo su hash en `usuarios`. No se proporcionan contraseñas predeterminadas ni se
guardan credenciales reales en el repositorio. Este script no se sirve por web.

## Backend del registro de documentos

`guardar-documento` requiere sesión y recibe `multipart/form-data` con los campos
`csrf_token`, `no_oficio`, `fecha_emision`, `fecha_recepcion`, `asunto`, `remitente`,
`destinatario` y `archivo_pdf`. Las fechas usan `AAAA-MM-DD`. El usuario se obtiene
de la sesión y el estado inicial es `Recibido`.

Devuelve JSON: 201 si se registra, 403 para token inválido, 409 para folio
duplicado, 422 para datos o archivo inválidos y 413 para una petición demasiado
grande. Documento e historial inicial se guardan en una transacción. Si falla el
registro se retira el PDF que se acababa de guardar.

Los PDFs usan nombres aleatorios dentro de `storage/documentos`, sin acceso web
directo. PHP necesita permiso de escritura en esa carpeta. `public/.user.ini`
configura un máximo de 25 MB por archivo y 27 MB por petición para PHP-FPM.
Al cambiar estos límites, PHP puede necesitar una recarga por su caché.

El formulario envía los datos mediante JavaScript y muestra una confirmación
con enlace al documento guardado. La bandeja permite buscar y filtrar por los
estados reales entre los registros cargados. En pantallas pequeñas las filas
se presentan como tarjetas y mantienen visible el acceso al detalle.

## Descarga del PDF

El enlace del detalle utiliza el ID del documento. El controlador consulta la
ruta en MySQL, comprueba que el archivo esté dentro de `storage/documentos` y lo
envía como descarga. No acepta rutas de archivo desde la URL ni publica la
carpeta de PDFs. Devuelve 400 para IDs inválidos y 404 si el documento o su PDF
no existen. La ficha indica cuando el archivo no está disponible.

## Estado e historial

El detalle incluye un selector con los estados de MySQL y un botón para guardar
el cambio. La petición requiere sesión, token CSRF y un documento y estado
válidos. La actualización y su movimiento en `historial_estatus` se guardan en
una misma transacción. El usuario del movimiento se toma de la sesión; se
conserva el usuario que registró originalmente el documento.

Guardar el estado actual no genera movimientos repetidos. El historial del
detalle muestra estado, usuario y fecha, comenzando por el movimiento más
reciente. Tras guardar se recarga el detalle con una confirmación; la bandeja
y sus conteos reflejan el estado nuevo al abrirla.

Se retiraron los controles de demostración de OCR, escaneo, prioridades,
borradores, turnados, reportes y acciones masivas, junto con las cifras y el
seguimiento ficticios.

Las pruebas se realizan temporalmente fuera del proyecto, retirando sus
registros y PDFs al terminar.
