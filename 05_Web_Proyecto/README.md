# ASHAB · sitio web (HTML + PHP + CSS + MySQL)

Yerba mate argentina. Catálogo público, ingreso con Google, aprobación de comercios, pedidos mayoristas (el pago se acuerda por WhatsApp o mail con un mensaje ya armado) y seguimiento hasta que el pedido se **embarca**. Sin JavaScript.

## Archivos
| Archivo | Para qué sirve |
|---|---|
| `index.php`, `productos.php`, `contacto.php` | Páginas públicas |
| `login.php`, `google.php`, `salir.php` | Ingreso con Google y salida |
| `perfil.php`, `panel.php`, `pedido.php` | Zona del cliente: datos del comercio, pedidos y envío del detalle por WhatsApp o mail |
| `admin/index.php`, `admin/exportar.php` | Administración y descarga a Excel (solo el Gmail administrador) |
| `inc/config.php` | Datos del negocio, administrador y estados del pedido |
| `inc/funciones.php`, `inc/conexion.php` | Funciones comunes y conexión PDO a MySQL |
| `inc/cabecera.php`, `inc/pie.php` | Se repiten en todas las páginas |
| `database.sql` | Crea la base `ashab` con sus tablas y los datos iniciales |

## Correrlo en XAMPP
1. Copiar esta carpeta en `C:\xampp\htdocs\ashab`.
2. Iniciar Apache y MySQL desde el panel de XAMPP.
3. En phpMyAdmin → **Importar** → elegir `database.sql`.
4. Abrir **http://localhost/ashab/**. Sin claves de Google, en esta computadora el ingreso pide solo el email (modo de prueba). Con `juancruzceresani@gmail.com` se entra como administrador.

## Ingreso con Google (en el sitio publicado)
1. En Google Cloud Console → *APIs y servicios* → *Credenciales* → **ID de cliente de OAuth** (aplicación web).
2. Como *URI de redireccionamiento* poner `https://TU-SITIO/google.php`.
3. Cargar las variables de entorno `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET`.
El modo de prueba **nunca** funciona en el sitio publicado: solo en `localhost`.

## Cómo se protege
- Consultas con `PDO` y parámetros (no hay SQL armado con texto del usuario).
- `h()` escapa todo lo que se muestra (evita XSS). Código CSRF en cada formulario.
- Los clientes no tienen contraseña: el ingreso es con Google (el administrador además puede entrar con una clave guardada como hash) y se validan emisor, destinatario, vencimiento y email verificado.
- Cada ingreso queda registrado con su IP; 20 intentos fallidos desde una IP bloquean 15 minutos.
- Las cuentas nuevas quedan *pendientes* hasta que el administrador las aprueba.
- El administrador es quien figura en `ADMIN_EMAILS` (`inc/config.php`).

## Publicado en Vercel
`api/index.php` recibe todas las direcciones y `inc/vercel.php` usa Postgres (Neon) en lugar de MySQL, con las mismas tablas. Variables: `DATABASE_URL`, `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `ADMIN_CLAVE_HASH` (opcional).
