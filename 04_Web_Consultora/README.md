# Web de Berasoft S.R.L.

Sitio institucional de la empresa: **Inicio, Servicios, Trabajos, Nosotros y Contacto** (sigue los bocetos de `03_Bocetos`). Paleta gris grafito y celeste, letra Montserrat.
Hecho con **HTML, CSS y PHP**, sin JavaScript. El menú del celular se abre con un checkbox de CSS.

- No usa base de datos. Los botones abren **WhatsApp** con el pedido de presupuesto ya escrito (función `whatsapp()` en `inc/datos.php`).
- **Inicio de sesión del socio** (`login.php` → `panel.php`, `salir.php`). La cuenta está en `inc/datos.php` con la contraseña encriptada (`password_hash`).
  Email: `juancruzceresani@gmail.com` · Contraseña: `berasoft2026`.
- Los datos de la empresa y los servicios están en un solo lugar: `inc/datos.php`.
- `inc/cabecera.php` y `inc/pie.php` se repiten en todas las páginas con `require`.

## Correrlo en XAMPP
Copiar la carpeta en `C:\xampp\htdocs\berasoft` y abrir **http://localhost/berasoft/**.

## Publicado
`api/index.php` y `vercel.json` son solo para Vercel: todas las direcciones pasan por `api/index.php`, que carga la página pedida si está en la lista blanca.
