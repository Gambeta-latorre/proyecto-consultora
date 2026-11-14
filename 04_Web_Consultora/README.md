# Web de Berasoft S.R.L.

Sitio institucional de la empresa: **Inicio, Servicios, Trabajos, Nosotros y Contacto** (sigue los bocetos de `03_Bocetos`). Paleta gris grafito y celeste, letra Montserrat.
Hecho con **HTML, CSS y PHP**, sin JavaScript. El menú del celular se abre con un checkbox de CSS.

- Es solo para ver: no tiene formularios, ni inicio de sesión, ni base de datos. Los botones abren **WhatsApp** con el pedido de presupuesto ya escrito (función `whatsapp()` en `inc/datos.php`).
- Los datos de la empresa y los servicios están en un solo lugar: `inc/datos.php`.
- `inc/cabecera.php` y `inc/pie.php` se repiten en todas las páginas con `require`.

## Correrlo en XAMPP
Copiar la carpeta en `C:\xampp\htdocs\berasoft` y abrir **http://localhost/berasoft/**.

## Publicado
`api/index.php` y `vercel.json` son solo para Vercel: todas las direcciones pasan por `api/index.php`, que carga la página pedida si está en la lista blanca.
