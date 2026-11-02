# Web de Berasoft S.R.L.

Sitio institucional de la empresa: **Inicio, Servicios, Trabajos, Nosotros y Contacto** (sigue los bocetos de `03_Bocetos`).
Hecho con **HTML, CSS y PHP**, sin JavaScript. El menú del celular se abre con un checkbox de CSS.

- No usa base de datos: el formulario de contacto arma el pedido de presupuesto y lo abre en **WhatsApp** (atención al cliente).
- Los datos de la empresa y los servicios están en un solo lugar: `inc/datos.php`.
- `inc/cabecera.php` y `inc/pie.php` se repiten en todas las páginas con `require`.

## Correrlo en XAMPP
Copiar la carpeta en `C:\xampp\htdocs\berasoft` y abrir **http://localhost/berasoft/**.

## Publicado
`api/index.php` y `vercel.json` son solo para Vercel: todas las direcciones pasan por `api/index.php`, que carga la página pedida si está en la lista blanca.
