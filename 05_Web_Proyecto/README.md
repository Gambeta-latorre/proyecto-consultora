# ASHAB · sitio web (HTML + CSS + PHP + MySQL)

Yerba mate argentina. Es un sitio **solo para ver**: muestra las yerbas y sus precios (leídos de la base MySQL) y cada yerba tiene un botón para **pedirla por WhatsApp** con el mensaje ya escrito. No tiene inicio de sesión, ni formularios, ni panel. Sin JavaScript.

## Archivos
| Archivo | Para qué sirve |
|---|---|
| `index.php` | Inicio: portada, yerbas con precio al público y cómo comprar |
| `productos.php` | Catálogo completo con precio al público, precio por mayor y botón de WhatsApp |
| `contacto.php` | WhatsApp, horario y email |
| `inc/config.php` | Datos del negocio (número de WhatsApp) y funciones `h()`, `pesos()` y `whatsapp()` |
| `inc/conexion.php` | Conexión PDO a MySQL |
| `inc/cabecera.php`, `inc/pie.php` | Se repiten en todas las páginas |
| `database.sql` | Crea la base `ashab`, la tabla `productos` y carga las 4 yerbas |
| `api/index.php`, `inc/vercel.php`, `vercel.json` | Solo para el sitio publicado en Vercel |

## Correrlo en XAMPP
1. Copiar esta carpeta en `C:\xampp\htdocs\ashab`.
2. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
3. Abrir http://localhost/phpmyadmin → **Importar** → elegir `database.sql` → **Importar**.
4. Abrir **http://localhost/ashab/**.

Para cambiar un precio: phpMyAdmin → base `ashab` → tabla `productos` → **Editar** → guardar. Al recargar la página aparece el precio nuevo.
Para ocultar una yerba: poner `activo` en 0.
