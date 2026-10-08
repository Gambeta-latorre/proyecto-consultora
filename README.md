# Rumbo Global Consultores S.A. + ASHAB (أعشاب الواحة)

Proyecto académico: una consultora de comercio exterior (**Rumbo Global Consultores S.A.**) y su cliente, una yerbatera de Misiones (ficticia) que quiere exportar yerba mate con la marca **ASHAB / أعشاب الواحة** («hierbas del oasis») a **Siria, Líbano, Jordania y Emiratos Árabes Unidos**.

> Los datos del cliente y los números del proyecto son **ficticios**: precios, fletes, aranceles y tipo de cambio son supuestos editables y hay que validarlos con cotizaciones reales.

## Webs publicadas (Vercel)

| Sitio | Dirección |
|---|---|
| Rumbo Global Consultores S.A. | https://rumboglobal.vercel.app |
| ASHAB (español, inglés y árabe) | https://ashab-omega.vercel.app |

Cada una usa su propia base Postgres (Neon). El panel de administración está en `/admin` y solo entra el correo configurado en `ADMIN_EMAILS`. Para activar el botón «Continuar con Google» faltan las credenciales de Google (ver [`DEPLOY_VERCEL.md`](DEPLOY_VERCEL.md), sección 4): sin ellas, la web de ASHAB muestra el aviso de que el ingreso con Google todavía no está configurado, y la de la consultora sigue permitiendo ingresar con DNI y contraseña.

## Qué hay en el repositorio

| Carpeta / archivo | Contenido |
|---|---|
| [`01_Logo/`](01_Logo) | Logo de la consultora (rojo y negro, globo terráqueo con avión): PNG, SVG, versión en negativo e isotipo |
| [`02_Presentaciones/`](02_Presentaciones) | Presentación de la consultora, del proyecto y de los diagramas (PowerPoint), y la **versión editable para Google Slides** |
| [`03_Diagramas/`](03_Diagramas) | 3 diagramas de flujo simples (S1–S3) y 6 complejos (C1–C6) en PNG |
| [`04_Web_Consultora/`](04_Web_Consultora) | Web PHP de la consultora: WhatsApp flotante, ingreso con DNI + contraseña o Google, panel de administración |
| [`05_Web_Proyecto/`](05_Web_Proyecto) | Web PHP de ASHAB en **español, inglés y árabe (RTL)**: ingreso con Google, empresas aprobadas, pedidos con **seña del 50 %**, panel de administración |
| [`06_Documentos/`](06_Documentos) | Manual de usuario, costo del proyecto (Word + Excel), cultura, seguridad e higiene, ciclo de trabajo y la guía de WhatsApp / identificación / seña |
| [`DEPLOY_VERCEL.md`](DEPLOY_VERCEL.md) | Cómo publicar las dos webs en Vercel con base Postgres |
| [`_shared/`](_shared) y [`_build/`](_build) | Núcleo PHP compartido, generadores de presentaciones/documentos/diagramas y pruebas automáticas |

## Decisiones de diseño

- **WhatsApp**: un solo número internacional (+54 9 11 3348-6017 → `wa.me/5491133486017`), botón flotante, QR, botón «Copiar número», mensaje en el idioma del cliente y horarios en hora de Argentina y de la región. Detalle en [`06_Documentos/Guia_WhatsApp_Identificacion_y_Sena.docx`](06_Documentos/Guia_WhatsApp_Identificacion_y_Sena.docx).
- **Identificación en Medio Oriente**: no se pide documento personal. Se ingresa con Google y se carga el **registro comercial de la empresa**, que un administrador aprueba antes de mostrar precios.
- **Seña del 50 %**: ningún pedido se confirma sin la seña acreditada en el banco; la proforma vence a los 5 días; mínimo 1.000 kg y máximo 3 pedidos abiertos; el sistema no deja pasar a producción sin seña.
- **Control del administrador**: `/admin` muestra todos los usuarios, todos los inicios de sesión (IP, país, navegador, intentos fallidos), pedidos y consultas, con exportación a CSV. Las contraseñas (solo en la web de la consultora) se guardan con hash irreversible.
- **Vercel**: PHP con `vercel-php`, sesiones en base de datos y Postgres externo. Ver [`DEPLOY_VERCEL.md`](DEPLOY_VERCEL.md).

## Probar localmente

```bash
cd 05_Web_Proyecto
export DB_DSN="sqlite:$(pwd)/dev.sqlite" SETUP_TOKEN=dev ADMIN_EMAILS=tu@correo.com
php -S localhost:8000 -t public router.php   # luego abrir /install?token=dev
```

Pruebas automáticas (PHP 8.1+ y Python 3): `python _build/tests/test_ashab.py` y `python _build/tests/test_consultora.py`.

## Diagramas editables

Los diagramas de flujo están en [`02_Presentaciones/04_Diagramas_Editables_para_Google_Slides.pptx`](02_Presentaciones/04_Diagramas_Editables_para_Google_Slides.pptx) con cada caja, rombo y flecha como objeto editable. Para pasarlos a una página gratuita con diapositivas, transiciones y guardado automático: entrá a [Google Slides](https://slides.google.com) > **Archivo > Importar diapositivas > Subir** y elegí el archivo.

## Avisos

- Los textos legales (términos, seña, privacidad) son un **modelo de trabajo**; deben revisarlos un abogado.
- El documento de seguridad e higiene es orientativo y debe validarlo un profesional matriculado.
- Las traducciones al árabe deberían ser revisadas por un hablante nativo antes de publicar.
- Fuentes de las cifras de mercado: INYM, Bolsa de Comercio de Rosario, Infocampo e iProfesional (las fuentes difieren en la participación exacta de Siria: 64 a 77 %).
