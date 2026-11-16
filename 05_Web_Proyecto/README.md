# ASHAB · sitio web (HTML + CSS + PHP + MySQL)

Yerba mate argentina. Muestra las yerbas y sus precios (leídos de la base MySQL) y cada yerba tiene un botón para **pedirla por WhatsApp** con el mensaje ya escrito.
Los clientes pueden **crear una cuenta e iniciar sesión**: los usuarios se guardan en MySQL (tabla `usuarios`, contraseña encriptada) y quien inicia sesión ve el **precio por mayor**. Sin JavaScript.

Usuario de prueba: `demo@ashab.com` · contraseña `ashab123`.

## Archivos
| Archivo | Para qué sirve |
|---|---|
| `index.php` | Inicio: portada, yerbas con precio al público y cómo comprar |
| `productos.php` | Catálogo completo con precio al público, precio por mayor y botón de WhatsApp |
| `contacto.php` | WhatsApp, horario y email |
| `registro.php`, `login.php`, `salir.php` | Crear cuenta, iniciar sesión y cerrar sesión (tabla `usuarios`) |
| `inc/config.php` | Abre la sesión, datos del negocio y funciones `h()`, `pesos()`, `whatsapp()` y `usuario_actual()` |
| `inc/conexion.php` | Conexión PDO a MySQL |
| `inc/cabecera.php`, `inc/pie.php` | Se repiten en todas las páginas |
| `database.sql` | Crea la base `ashab`, las tablas `productos` (4 yerbas) y `usuarios` (con el usuario de prueba) |
| `api/index.php`, `inc/vercel.php`, `vercel.json` | Solo para el sitio publicado en Vercel |

## Correrlo en XAMPP
1. Copiar esta carpeta en `C:\xampp\htdocs\ashab`.
2. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
3. Abrir http://localhost/phpmyadmin → **Importar** → elegir `database.sql` → **Importar**.
4. Abrir **http://localhost/ashab/**.

Para cambiar un precio: phpMyAdmin → base `ashab` → tabla `productos` → **Editar** → guardar. Al recargar la página aparece el precio nuevo.
Para ocultar una yerba: poner `activo` en 0.
