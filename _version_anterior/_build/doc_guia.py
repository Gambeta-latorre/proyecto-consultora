import sys
sys.path.insert(0, "_build")
from dx import Doc

D = Doc("ash", "Guía WhatsApp, identificación y seña · Proyecto ASHAB")
D.cover("WhatsApp, identificación y seña", "Cómo se comunican, cómo se identifican y cómo se aseguran los pedidos de los clientes de Medio Oriente",
        ["Proyecto ASHAB · Rumbo Global Consultores S.A.", "Versión 1.0 · 2026",
         "Documento de decisión: lo que se implementó en las webs y por qué."])

D.h1("1. Resumen de decisiones")
D.table([["Tema", "Decisión", "Dónde está implementado"],
         ["WhatsApp", "Un solo número en formato internacional (+54 9 11 3348-6017), botón flotante con mensaje en el idioma del cliente, QR y botón «Copiar número».", "Ambas webs, página Contacto y botón flotante"],
         ["Identificación", "Se quita el DNI para los clientes de Medio Oriente. Ingresan solo con Google y cargan el registro comercial de su empresa. Un administrador aprueba cada empresa.", "Web de ASHAB"],
         ["Seña", "Ningún pedido se confirma sin una seña del 50 % acreditada en el banco. La proforma vence a los 5 días.", "Web de ASHAB, sección Pedidos"],
         ["Acceso del administrador", "Panel con todos los usuarios, todos los ingresos (con IP y país) y todos los pedidos, con descarga a Excel.", "Ambas webs, /admin"],
         ["Web pública", "Cada web se publica como un proyecto de Vercel, con base de datos Postgres externa.", "Carpetas 04_Web_Consultora y 05_Web_Proyecto"]],
        widths=[3, 9.5, 4], size=10, bold_first_col=True)

D.h1("2. WhatsApp: cómo lo ven y cómo escriben los clientes árabes")
D.h2("2.1 El número es el mismo para todo el mundo")
D.p("WhatsApp identifica cada línea con el formato internacional: signo +, código del país, número. El de Rumbo Global es +54 9 11 3348-6017: 54 es Argentina, 9 es la marca de los celulares argentinos para WhatsApp y 11 es Buenos Aires. Un cliente de Dubái, Beirut, Amán o Damasco ve exactamente ese número; lo único que cambia es cómo lo escribiría si tuviera que marcarlo a mano.")
D.table([["Si el cliente está en…", "Marcaría a mano", "Con el botón del sitio"],
         ["Emiratos, Jordania, Líbano, Siria", "00 54 9 11 3348 6017 (o +54 9 11 3348 6017)", "Un toque: se abre el chat con el mensaje listo"],
         ["Cualquier país", "Nunca debería escribir 11 3348-6017 solo: sin el código +54 no llega", "No necesita escribir nada"]],
        widths=[4.5, 7, 5], size=10, bold_first_col=True)
D.h2("2.2 Problemas típicos y cómo los resolvimos")
D.table([["Problema", "Por qué pasa", "Solución en la web"],
         ["El número aparece con el + al final en la página en árabe", "Los textos de derecha a izquierda reordenan los símbolos de un número", "Se muestra dentro de una etiqueta con dirección de izquierda a derecha (bdi dir=ltr)"],
         ["Dígitos distintos (٠١٢٣ o 0123)", "Algunos teléfonos y teclados de la región usan dígitos arábigo-índicos", "El sitio muestra siempre dígitos occidentales y el enlace wa.me no necesita que el cliente los escriba"],
         ["Escriben «11 3348 6017» o «011 15…»", "Es la forma local argentina, no sirve desde el exterior", "La página dice que el número ya incluye +54 y el 9; el botón evita escribirlo"],
         ["Desde la computadora no tienen WhatsApp instalado", "Es común en oficinas", "Código QR en Contacto para escanear con el teléfono, y WhatsApp Web como alternativa"],
         ["Mensaje en el idioma equivocado", "El cliente no sabe qué escribir", "El mensaje precargado sale en español, inglés o árabe según el idioma elegido, e incluye el producto o el código del pedido"],
         ["Diferencia horaria", "Argentina está en UTC-3 todo el año; Siria y Jordania en UTC+3; Emiratos en UTC+4", "La página muestra el horario de atención en hora de Argentina y en hora de la región"]],
        widths=[4.5, 5.5, 6.5], size=9.5, bold_first_col=True)
D.h2("2.3 Recomendaciones")
D.bullets(["Usar la aplicación WhatsApp Business con el mismo número: permite mensaje de bienvenida, mensaje de ausencia en inglés y árabe, etiquetas por cliente y catálogo.",
           "En Emiratos las llamadas de voz por WhatsApp pueden estar restringidas; el chat funciona. Para llamadas conviene acordar otro medio.",
           "Tener también un correo de empresa para enviar proformas y contratos con firma: WhatsApp es para coordinar, no para cerrar el contrato.",
           "Pedir que la empresa registre su propio WhatsApp con código de país en el perfil: el administrador ve ese número en cada pedido y puede escribirle con un toque."])

D.h1("3. Identificación: por qué se quitó el DNI para Medio Oriente")
D.h2("3.1 Lo que se pensó")
D.p("La idea original era usar el documento de identidad como forma de ingreso y para poder denunciar a quien haga pedidos falsos. En Medio Oriente existen documentos equivalentes, pero no es una buena base de seguridad por estos motivos:")
D.bullets([("Cada país tiene un formato distinto. ", "Siria usa el número nacional; Líbano, el número de registro civil; Jordania, un número nacional de 10 dígitos; Emiratos, el Emirates ID de 15 dígitos; Arabia Saudita, 10 dígitos. Habría que validar y mantener cuatro o cinco formatos."),
           ("No sirve para denunciar desde Argentina. ", "Un documento personal extranjero no permite iniciar acciones por sí solo. Lo que respalda una reclamación es el contrato, la proforma aceptada, los datos legales de la empresa y la prueba del pago."),
           ("Es un dato muy sensible. ", "Guardar documentos personales de ciudadanos de otros países te expone a leyes de protección de datos (por ejemplo, la ley de protección de datos de Emiratos) y a que una filtración sea un problema grave."),
           ("No impide el fraude. ", "Cualquiera puede escribir un número. Solo una verificación real del documento serviría, y requeriría un proveedor de identidad pago por país.")])
D.h2("3.2 Lo que se implementó")
D.numbered(["Ingreso solo con Google. Google verifica el correo y protege la cuenta con su propia seguridad (contraseña fuerte, verificación en dos pasos). El sitio no guarda contraseñas.",
            "Perfil de empresa obligatorio: nombre, país, ciudad, número de registro comercial o licencia comercial, WhatsApp con código de país y cargo.",
            "Aprobación manual por el administrador antes de mostrar precios y aceptar pedidos. Se verifica el registro de la empresa en fuentes públicas.",
            "Registro de cada ingreso con fecha, IP, país aproximado y navegador, visible para el administrador.",
            "Seña del 50 % para confirmar (sección 4)."])
D.table([["País", "Qué número de empresa se pide", "Dónde conviene verificarlo"],
         ["Siria", "Número de registro comercial (السجل التجاري)", "Cámara de comercio de la ciudad y registro comercial oficial"],
         ["Líbano", "Número de registro de comercio (السجل التجاري)", "Registro de comercio del Ministerio de Justicia y cámara de comercio de Beirut"],
         ["Jordania", "Número de registro de la empresa", "Departamento de Control de Sociedades y cámaras de comercio o industria"],
         ["Emiratos", "Licencia comercial (Trade License)", "Registro de la autoridad económica del emirato (por ejemplo, Dubai Economy and Tourism)"],
         ["Otros países", "Registro mercantil o número fiscal", "Registro público del país"]],
        widths=[3, 6.5, 7], size=10, bold_first_col=True)
D.callout("Si de todas formas necesitás el documento personal", "Pedilo solo del representante legal que firma el contrato, por fuera del sitio web (por ejemplo, adjunto en el contrato firmado), y guardalo según la ley de protección de datos. No lo uses como forma de ingreso.", "warn")
D.callout("Cuenta de la consultora", "El sitio de Rumbo Global mantiene el ingreso con DNI y contraseña para clientes argentinos, y además permite entrar con Google.", "info")

D.h1("4. Seña del 50 % y otras protecciones contra pedidos sin sentido")
D.h2("4.1 El flujo")
D.image("03_Diagramas/C6_pedido_con_sena_50.png", 16.5, "Figura 1. Pedido con seña del 50 %.")
D.numbered(["El cliente aprobado pide una cotización (mínimo 1.000 kg, máximo 48.000 kg, hasta 3 pedidos abiertos).",
            "El administrador emite la proforma con precio, total, seña y vencimiento (5 días).",
            "El cliente acepta los términos, transfiere el 50 % e informa banco, referencia, fecha y monto. El sitio registra la aceptación con fecha e IP.",
            "El administrador comprueba el dinero en el banco y marca «seña acreditada». Recién entonces el pedido está confirmado y se produce.",
            "El saldo del 50 % se cobra antes de cargar o contra copia del B/L.",
            "Si la seña no llega en 5 días, la proforma vence sola y el pedido se cancela."])
D.h2("4.2 Capas de protección")
D.table([["Capa", "Qué evita"],
         ["Empresa aprobada con registro comercial", "Cuentas falsas o personas sin empresa real"],
         ["Mínimo de 1.000 kg y máximo de 3 pedidos abiertos", "Pedidos de prueba y acaparamiento de cotizaciones"],
         ["Proforma con vencimiento de 5 días", "Precios congelados indefinidamente"],
         ["Seña del 50 % acreditada antes de producir", "Producir y reservar flete para un cliente que desaparece"],
         ["Aceptación de términos con fecha e IP", "Discusiones sobre qué se aceptó y cuándo"],
         ["El sistema impide pasar a producción sin seña", "Errores humanos del equipo"],
         ["Registro de ingresos y exportación a Excel", "Accesos sospechosos y falta de pruebas"]],
        widths=[7.5, 9], size=10, bold_first_col=True)
D.h2("4.3 Cómo cobrar la seña")
D.p("El sitio no procesa pagos. Esto es a propósito: las tarjetas y las pasarelas de pago tienen restricciones en varios de estos países (en Siria, por ejemplo, siguen existiendo restricciones bancarias y de procesadores de pago que conviene consultar con el banco) y una transferencia bancaria evita contracargos. Lo habitual en comercio exterior es:")
D.bullets([("Transferencia bancaria internacional (SWIFT) ", "a la cuenta de la yerbatera o de la consultora, con el código del pedido como referencia. Es irreversible una vez acreditada."),
           ("Carta de crédito confirmada ", "para pedidos grandes: un banco garantiza el pago contra documentos. Se acuerda por escrito antes de emitir la proforma."),
           ("Otros medios ", "(plataformas de pagos internacionales, casas de cambio) solo si su banco o la plataforma confirman que operan entre Argentina y ese país.")])
D.p("Los datos bancarios que ve el cliente se cargan en la variable BANK_INFO del servidor, no en el código.")
D.h2("4.4 Advertencias legales y de seguridad")
D.bullets(["La cláusula de seña no reembolsable debe revisarla un abogado. En Argentina, el Código Civil y Comercial distingue entre seña confirmatoria y arras penitenciales; hay que redactar con claridad qué pasa si el comprador se arrepiente.",
           "Controlar sanciones internacionales antes de aceptar un comprador, sobre todo en Siria y Líbano: verificar que ni la empresa, ni sus dueños, ni el banco estén en listas de sanciones, y consultar con el banco si acepta la operación.",
           "Desconfiar de pagos hechos por un tercero que no es el comprador y de «pagos de más con pedido de devolución de la diferencia»: es una estafa conocida.",
           "No iniciar producción por mensaje de WhatsApp: solo con la seña acreditada en el estado «Seña acreditada» del sitio.",
           "Los textos de términos y privacidad del sitio son un modelo de trabajo, no asesoramiento legal."])

D.h1("5. Acceso del administrador a los datos")
D.bullets([("Panel /admin: ", "disponible para los correos de Google incluidos en ADMIN_EMAILS. Muestra usuarios, inicios de sesión, pedidos y consultas."),
           ("Exportación a Excel: ", "botones CSV en el Resumen (usuarios, accesos, pedidos, consultas)."),
           ("Base de datos: ", "los datos viven en tu propia base Postgres (por ejemplo Neon), que podés abrir con su consola SQL o con una herramienta como DBeaver."),
           ("Contraseñas: ", "en ASHAB no existen. En la web de la consultora, las claves de quienes se registran con DNI se guardan cifradas con un hash irreversible; es una protección, no una limitación: ni vos ni un atacante pueden leerlas.")])

D.h1("6. Presentación de diagramas editable")
D.p("Los diagramas de flujo pasaron a una presentación de Google Slides: es gratuita, tiene transiciones y modo presentación, se guarda sola en tu Drive y se edita como cualquier diapositiva (cada caja y flecha es un objeto que podés mover, recolorear o borrar). También se puede exportar a PowerPoint o PDF desde Archivo > Descargar. El enlace figura en el README del repositorio.")
D.save("06_Documentos/Guia_WhatsApp_Identificacion_y_Sena.docx")
print("guia ok")
