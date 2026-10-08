# Sitio web (PHP en Vercel)

- Estructura: `api/index.php` (enrutador), `src/` (núcleo, idioma, páginas y plantillas), `public/` (CSS e imágenes), `database/` (esquemas Postgres, MySQL y SQLite).
- Publicación en Vercel: ver [`../DEPLOY_VERCEL.md`](../DEPLOY_VERCEL.md).
- Desarrollo local: `php -S localhost:8000 -t public router.php` con `DB_DSN=sqlite:ruta/dev.sqlite` y `SETUP_TOKEN` definidos; abrir `/install?token=...` una vez.
- Variables de entorno: ver `.env.example`.
- `src/core.php` es una copia de `../_shared/core.php`: editar la de `_shared` y correr `python _build/sync_core.py`.
