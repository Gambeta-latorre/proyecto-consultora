# Publicar las dos webs en Vercel (acceso público)

Se publican **dos proyectos de Vercel desde este mismo repositorio**, cada uno con su carpeta raíz:

| Proyecto de Vercel | Root Directory | Qué es |
|---|---|---|
| `rumbo-global` | `04_Web_Consultora` | Web de la consultora (ingreso con DNI + Google) |
| `ashab` | `05_Web_Proyecto` | Web de ASHAB en español, inglés y árabe (ingreso solo con Google, pedidos con seña del 50 %) |

Vercel no ejecuta PHP de forma nativa: usamos el runtime comunitario [`vercel-php`](https://github.com/vercel-community/php) (ya configurado en cada `vercel.json`, PHP 8.5). Como cada página corre en una función distinta, **las sesiones se guardan en la base de datos** y los datos viven en una base **Postgres externa** (Neon, gratuita).

> Cada web necesita **su propia base de datos** (las dos usan tablas con el mismo nombre).

## 0. Cuentas necesarias (todas con plan gratuito)

- GitHub (ya tenés el repositorio).
- [Vercel](https://vercel.com) (plan Hobby). Importante: el plan Hobby es para uso personal y no comercial; si el sitio va a vender de verdad, corresponde el plan Pro.
- [Neon](https://neon.tech) (se conecta desde Vercel, sección *Storage*).
- [Google Cloud Console](https://console.cloud.google.com) (para el botón «Continuar con Google»).

## 1. Crear los proyectos en Vercel

1. En Vercel: **Add New > Project** e importá el repositorio.
2. **Root Directory**: `05_Web_Proyecto` (y repetí el proceso con `04_Web_Consultora`).
3. **Framework Preset**: *Other*. No hace falta comando de build.
4. En **Settings > General > Node.js Version** elegí **22.x** (el `package.json` ya lo pide).
5. Todavía no hagas *Deploy* hasta cargar las variables del paso 3 (o hacelo y volvé a desplegar después).

## 2. Base de datos Postgres

1. En cada proyecto: **Storage > Create Database > Neon (Postgres)**. Se agrega sola la variable `DATABASE_URL` (y las `POSTGRES_*`).
2. No hay que importar ningún archivo SQL a mano: las tablas se crean con la página `/install` (paso 5). Los esquemas están en `database/postgres.sql`.

## 3. Variables de entorno (Settings > Environment Variables)

| Variable | Valor | Obligatoria |
|---|---|---|
| `DATABASE_URL` | La agrega Neon | Sí |
| `GOOGLE_CLIENT_ID` | De Google Cloud (paso 4) | Para el ingreso con Google |
| `GOOGLE_CLIENT_SECRET` | De Google Cloud (paso 4) | Para el ingreso con Google |
| `ADMIN_EMAILS` | Tu correo de Google (varios separados por coma) | Sí: quien figura acá ve `/admin` |
| `SETUP_TOKEN` | Una clave larga al azar | Solo para crear las tablas |
| `BANK_INFO` | Datos bancarios para la seña (texto, `\n` = salto de línea) | ASHAB |
| `APP_URL` | `https://tu-dominio` (si usás dominio propio) | Opcional |
| `PROYECTO_URL` | URL de la web de ASHAB (para el enlace de la web de la consultora) | Opcional |

No subas nunca claves al repositorio. El archivo `.env.example` solo muestra los nombres.

## 4. Ingreso con Google (una sola vez por web)

1. En Google Cloud Console creá un proyecto y entrá a **APIs y servicios > Pantalla de consentimiento de OAuth**.
2. Tipo de usuario **Externo**, nombre de la app (por ejemplo «ASHAB»), correo de soporte y tu correo de contacto. Alcances: `openid`, `email`, `profile` (no requieren verificación de Google).
3. **Publicá la app** («En producción»). Si queda en *Prueba*, solo entran los usuarios de prueba y las sesiones caducan a los 7 días.
4. **Credenciales > Crear credenciales > ID de cliente de OAuth > Aplicación web**.
5. En **URI de redireccionamiento autorizados** agregá:
   - `https://TU-PROYECTO.vercel.app/google_callback`
   - `http://localhost:8000/google_callback` (para pruebas locales)
6. Copiá el *ID de cliente* y el *secreto* a las variables `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET` de Vercel.

## 5. Crear las tablas

1. Con `SETUP_TOKEN` cargada, desplegá y visitá: `https://TU-PROYECTO.vercel.app/install?token=EL_VALOR_DE_SETUP_TOKEN`
2. Deberías ver «Listo». En ASHAB también carga 4 productos de ejemplo.
3. **Borrá la variable `SETUP_TOKEN`** y volvé a desplegar: así `/install` queda desactivado.

## 6. Probar

- Entrá con el correo que pusiste en `ADMIN_EMAILS`: vas directo a `/admin`.
- En ASHAB, probá con otra cuenta de Google: completá el perfil de empresa y aprobala desde `/admin > Usuarios`.
- Revisá en `/admin` que aparezcan los inicios de sesión (IP y país incluidos) y descargá el CSV.

## 7. Qué ve el administrador

`/admin` muestra todos los usuarios, **todos los inicios de sesión** (correctos y fallidos), pedidos y consultas, con exportación a CSV. Las contraseñas del sitio de la consultora (ingreso con DNI) se guardan con hash irreversible: nadie puede leerlas. Los datos también están en tu base Neon, que podés abrir desde su consola SQL.

## 8. Problemas frecuentes

| Síntoma | Qué mirar |
|---|---|
| Pantalla «Service temporarily unavailable» | Vercel > *Logs* del proyecto. Agregá `APP_DEBUG=1` unos minutos para ver el error en pantalla. Revisá `DATABASE_URL` y que hayas corrido `/install`. |
| `redirect_uri_mismatch` en Google | El URI de redireccionamiento de Google Cloud debe ser idéntico a `https://TU-DOMINIO/google_callback`. |
| «El ingreso con Google todavía no está configurado» | Faltan `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET`. Volvé a desplegar después de cargarlas. |
| No veo el enlace «Administración» | Tu correo de Google no está en `ADMIN_EMAILS` (sin espacios, minúsculas). |
| Las páginas cargan lento la primera vez | Arranque en frío de la función y de Neon (plan gratuito). Normal. |

## 9. Probar en tu computadora (sin Vercel)

Requiere PHP 8.1 o superior con `pdo_sqlite` (o MySQL).

```bash
cd 05_Web_Proyecto
export DB_DSN="sqlite:$(pwd)/dev.sqlite" SETUP_TOKEN=dev ADMIN_EMAILS=tu@correo.com APP_URL=http://localhost:8000
php -S localhost:8000 -t public router.php
# abrir http://localhost:8000/install?token=dev  y después http://localhost:8000/
```

Pruebas automáticas de punta a punta (con un Google simulado):

```bash
python _build/tests/test_ashab.py
python _build/tests/test_consultora.py
```
