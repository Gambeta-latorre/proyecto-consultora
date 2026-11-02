import sys
sys.path.insert(0, "_build")
from dx import Doc

D = Doc("ash", "Manual de usuario · Proyecto ASHAB")
D.cover("Manual de usuario", "Proyecto ASHAB · أعشاب الواحة\nYerba mate argentina para Medio Oriente",
        ["Versión 2.0 · 2026", "Preparado por Rumbo Global Consultores S.A.", "Consultas por WhatsApp: 11 3348-6017 (+54 9 11 3348-6017)"])

D.h1("1. Para qué sirve este manual")
D.p("Este manual explica, paso a paso y sin términos técnicos, cómo usar el sitio web del proyecto ASHAB, cómo se confirma un pedido con la seña del 50 % y cómo la persona administradora controla todo. Está pensado para tres tipos de personas:")
D.bullets([("Compradores y distribuidores", " (Siria, Líbano, Jordania, Emiratos y otros países) que quieren ver el catálogo y pedir precios."),
           ("El equipo de la yerbatera", " que recibe y gestiona los pedidos."),
           ("La persona administradora", " que aprueba empresas, emite proformas, verifica pagos y mantiene el sitio.")])
D.callout("Idiomas", "El sitio está en español, inglés y árabe. El árabe se muestra de derecha a izquierda, como corresponde. El panel de administración está en español.", "info")

D.h1("2. Qué necesitás")
D.bullets(["Un teléfono, tableta o computadora con internet y un navegador actualizado (Chrome, Edge, Firefox o Safari).",
           "Una cuenta de Google con el correo verificado. No hace falta que sea @gmail.com: sirve cualquier correo vinculado a Google, incluso el de tu empresa.",
           "Los datos de tu empresa: nombre, país, ciudad, número de registro comercial, WhatsApp y cargo.",
           "WhatsApp, para recibir la proforma y consultar de forma directa."])
D.callout("No pedimos tu documento personal", "El sitio no te pide DNI, pasaporte ni Emirates ID. Para saber que la empresa existe usamos su número de registro comercial o licencia comercial. Así protegemos tus datos personales.", "info")

D.h1("3. Entrar al sitio y elegir el idioma")
D.numbered(["Abrí la dirección web de ASHAB en tu navegador.",
            "Arriba vas a ver tres nombres: Español, English y العربية. Tocá el que prefieras.",
            "El sitio se acuerda de tu elección la próxima vez. La primera vez usa el idioma de tu navegador."])
D.image("03_Diagramas/S2_como_se_usa_la_web_simple.png", 15.5, "Figura 1. Pasos para usar la página web, explicado simple.")

D.h1("4. Ingresar con Google")
D.numbered(["Tocá «Ingresar» y después «Continuar con Google».", "Elegí tu cuenta de Google y tocá «Continuar».", "Volvés al sitio ya con la sesión iniciada."])
D.p("Google nunca nos envía tu contraseña: solo nos confirma tu nombre y que tu correo está verificado. Si tenés varias cuentas, Google te deja elegir.")
D.callout("Si el ingreso no funciona", "Revisá que el correo de tu cuenta de Google esté verificado y que no hayas cancelado la pantalla de Google. Después de varios intentos fallidos desde la misma conexión, el sitio espera unos minutos antes de dejarte probar de nuevo.", "warn")

D.h1("5. Datos de tu empresa y aprobación")
D.p("La primera vez, el sitio te pide los datos de tu empresa. Se completan una sola vez y los revisa una persona antes de mostrarte precios y aceptar pedidos.")
D.table([["Campo", "Qué poner", "Ejemplo"],
         ["Nombre de la empresa", "Razón social o nombre comercial", "Beirut Trading"],
         ["País y ciudad", "Donde está registrada la empresa", "Líbano · Beirut"],
         ["Registro comercial", "Cambia según el país: Siria, registro comercial; Líbano, registro de comercio; Jordania, registro de la empresa; Emiratos, licencia comercial (Trade License)", "RC-123456"],
         ["WhatsApp de la empresa", "Con el código de tu país, para que podamos escribirte", "+961 3 123 456"],
         ["Cargo y sitio web", "Opcionales", "Compras · https://empresa.com"]],
        widths=[3.8, 8.2, 4.5], size=10.5, bold_first_col=True)
D.p("Mientras revisamos (1 a 2 días hábiles) tu cuenta figura «En revisión». Para acelerar, tocá el botón «Avisar por WhatsApp que me registré». Cuando la aprobemos vas a ver los precios.")
D.table([["Estado de la cuenta", "Qué significa"], ["En revisión", "Estamos controlando los datos de tu empresa. Todavía no se ven precios."],
         ["Aprobada", "Podés ver precios y pedir cotizaciones."], ["Rechazada", "No pudimos verificar la empresa. Escribinos por WhatsApp para aclararlo."]],
        widths=[4, 12.5], size=10.5, bold_first_col=True)

D.h1("6. Ver el catálogo y pedir una cotización")
D.p("En «Productos» están las cuatro variedades de ASHAB: Tradicional, Suave, con Menta y Premium. Cada tarjeta muestra la descripción y la presentación (bolsa y caja).")
D.bullets(["Sin sesión: aparece «Ingresá para ver el precio».", "Con la cuenta en revisión: aparece «Disponible cuando aprobemos tu empresa».",
           "Con la cuenta aprobada: ves el precio FOB indicativo en dólares por kilo y el formulario de pedido."])
D.numbered(["En el producto que te interesa completá «Cantidad (kg)». El mínimo es 1.000 kg y el máximo 48.000 kg por pedido web.", "Elegí el país de destino y la condición (FOB o CIF).",
            "Tocá «Enviar solicitud». Vas a ver el mensaje de confirmación."])
D.callout("Límite de pedidos abiertos", "Podés tener hasta 3 pedidos abiertos al mismo tiempo. Si necesitás más, escribinos por WhatsApp. Un contenedor de 40 pies lleva 24.000 kg.", "info")

D.h1("7. Confirmar el pedido con la seña del 50 %")
D.p("Para asegurar que cada pedido sea real, ningún pedido se confirma sin pagar una seña del 50 % del total. Estos son los pasos:")
D.image("03_Diagramas/C6_pedido_con_sena_50.png", 16.5, "Figura 2. Del pedido a la confirmación, con la seña del 50 %.")
D.numbered(["Recibís la proforma en «Mi cuenta», en el pedido, y un aviso por WhatsApp. Muestra el precio por kilo, el total, la seña a pagar y el saldo.",
            "Revisá la proforma y los términos (enlace «Términos y seña» al pie de la página). La proforma vale 5 días corridos.",
            "Transferí el 50 % a la cuenta que figura en la proforma (transferencia bancaria internacional, o carta de crédito confirmada si se acordó por escrito).",
            "En la página del pedido tocá «Informar pago» y completá banco, referencia de la operación, fecha y monto. Marcá que aceptás los términos.",
            "Nosotros verificamos el dinero en el banco. Cuando está acreditado, el estado pasa a «Seña acreditada: pedido confirmado» y empezamos a producir.",
            "El otro 50 % (saldo) se paga antes de cargar el contenedor o contra copia del conocimiento de embarque (B/L), según diga la proforma."])
D.table([["Estado del pedido", "Qué significa"],
         ["Solicitud recibida", "Estamos preparando tu proforma."],
         ["Proforma emitida: falta la seña", "Tenés 5 días para pagar la seña. Si no, la proforma vence."],
         ["Pago informado: lo estamos verificando", "Esperamos que el dinero figure en nuestro banco."],
         ["Seña acreditada: pedido confirmado", "El pedido es firme. Se produce y se reserva el contenedor."],
         ["En producción · Embarcada · Cerrada", "Etapas siguientes hasta la entrega."],
         ["Cancelada o Proforma vencida", "El pedido no continúa. Podés hacer uno nuevo."]],
        widths=[6.5, 10], size=10.5, bold_first_col=True)
D.callout("Importante sobre la seña", "Si cancelás después de pagar la seña, la seña no se devuelve porque cubre producción, packaging a medida y reserva de flete. Si nosotros no podemos entregar, devolvemos la seña completa. Podés cancelar sin costo mientras la proforma no esté pagada.", "warn")

D.h1("8. Escribir por WhatsApp")
D.p("En todas las páginas hay un botón verde de WhatsApp. Al tocarlo se abre WhatsApp con un mensaje listo para enviar. Si lo tocás desde un producto o un pedido, el mensaje ya incluye el nombre del producto o el código del pedido, en el idioma que elegiste.")
D.bullets([("Número en formato internacional: ", "+54 9 11 3348-6017. Ya incluye el código de Argentina (+54) y el 9 de los celulares argentinos. No marques ceros ni prefijos locales."),
           ("Desde una computadora: ", "en la página de Contacto hay un código QR; escanealo con tu teléfono."),
           ("Copiar el número: ", "el botón «Copiar número» lo copia en el portapapeles."),
           ("Horario: ", "lunes a viernes de 9 a 18 h de Argentina (15 a 24 h en Siria, Jordania y Líbano; 16 a 01 h en Emiratos).")])

D.h1("9. Tu cuenta")
D.p("En «Mi cuenta» ves el estado de tu empresa, tus datos (podés editarlos) y la lista de tus pedidos con fecha, producto, kilos, destino, total y estado. Si cambiás el nombre de la empresa o su número de registro, la cuenta vuelve a revisión.")

D.h1("10. Para la persona administradora")
D.h2("10.1 Quién es administrador")
D.p("Es administrador cualquier correo de Google incluido en la variable de entorno ADMIN_EMAILS (separados por coma). No hace falta tocar la base de datos. Quien figure ahí ve el enlace «Administración» al ingresar.")
D.h2("10.2 Qué se ve en el panel")
D.table([["Pestaña", "Qué hace"],
         ["Resumen", "Cuentas por estado, pedidos por estado e ingresos de las últimas 24 horas (correctos y fallidos)."],
         ["Usuarios", "Lista completa: email, nombre, empresa, país, registro comercial, WhatsApp, último ingreso. Permite aprobar, rechazar, dejar en revisión, anotar una nota interna y desactivar o reactivar."],
         ["Inicios de sesión", "Registro de cada ingreso: fecha, quién, método, resultado, motivo, IP, país y navegador. Incluye los intentos fallidos."],
         ["Pedidos y seña", "Emitir la proforma (precio y validez), confirmar que la seña está acreditada en el banco, pasar a producción, embarcada y cerrada, o cancelar."],
         ["Consultas", "Mensajes enviados desde el formulario de contacto."]],
        widths=[3.8, 12.7], size=10.5, bold_first_col=True)
D.p("Todo se puede descargar a Excel (CSV) desde el Resumen: usuarios, inicios de sesión, pedidos y consultas.")
D.callout("Sobre las contraseñas", "En el sitio de ASHAB no existen contraseñas: se ingresa con Google. En el sitio de la consultora, las contraseñas de quienes se registran con DNI se guardan cifradas (hash) y nadie puede verlas, ni siquiera el administrador. Si alguien la olvida, se desbloquea su cuenta o se le crea una nueva.", "info")
D.h2("10.3 Aprobar una empresa")
D.numbered(["Entrá a Administración, pestaña «Usuarios».", "Buscá las cuentas «pendiente» y revisá empresa, país, número de registro y WhatsApp.",
            "Verificá el registro (registro público del país, página web de la empresa, una llamada o mensaje por WhatsApp).", "Elegí «aprobada» o «rechazada», escribí una nota si querés y tocá OK."])
D.h2("10.4 Emitir una proforma y confirmar la seña")
D.numbered(["En «Pedidos y seña» abrí el pedido nuevo.", "Escribí el precio por kilo y la validez (por defecto 5 días) y tocá «Emitir proforma». El sistema calcula total, seña y saldo.",
            "Avisale al cliente por el enlace de WhatsApp que aparece en el pedido.", "Cuando el cliente informe la transferencia, controlá en el banco que el dinero esté acreditado.",
            "Tocá «Confirmar: seña acreditada en el banco». Recién ahí se puede pasar el pedido a producción."])
D.p("El sistema no deja pasar a producción sin seña acreditada, ni saltar etapas. Las proformas no pagadas vencen solas al cumplirse el plazo.")
D.h2("10.5 Puesta en marcha en Vercel")
D.p("Los pasos completos están en el archivo DEPLOY_VERCEL.md del repositorio. En resumen: importar el repositorio en Vercel, agregar una base de datos Postgres, cargar las variables (credenciales de Google, ADMIN_EMAILS, datos bancarios) y visitar una sola vez la página de instalación para crear las tablas.")
D.h2("10.6 Cambiar productos y precios")
D.p("Los productos están en la tabla «productos» de la base de datos (nombre y descripción en los tres idiomas, presentación y precio FOB por kilo). Se editan desde el panel SQL de la base (por ejemplo, Neon) con instrucciones como: UPDATE productos SET precio_fob_kg = 2.20 WHERE id = 1;")

D.h1("11. Problemas frecuentes")
D.table([["Problema", "Qué hacer"],
         ["No puedo ingresar con Google", "Probá con otra cuenta de Google o desde una ventana privada. Revisá que el correo esté verificado."],
         ["Veo «En revisión» desde hace días", "Escribinos por WhatsApp con el nombre de tu empresa."],
         ["No veo los precios", "Tu empresa todavía no fue aprobada, o no iniciaste sesión."],
         ["«Ya tenés 3 pedidos abiertos»", "Completá o cancelá alguno antes de hacer otro."],
         ["La proforma venció", "Hacé un pedido nuevo: se emite otra proforma."],
         ["Informé el pago y sigue «lo estamos verificando»", "La transferencia internacional puede tardar 1 a 3 días hábiles en acreditarse. Escribinos si pasan más."],
         ["El texto en árabe se ve cortado o al revés", "Actualizá el navegador y elegí «العربية» arriba."],
         ["El botón de WhatsApp no abre", "Instalá WhatsApp o usá WhatsApp Web desde la computadora."]],
        widths=[6, 10.5], size=10.5, bold_first_col=True)

D.h1("12. Glosario")
D.table([["Término", "Significado simple"],
         ["Proforma", "Presupuesto formal con precio, cantidad, total, seña y plazo. Es la base del pedido."],
         ["Seña", "Pago anticipado del 50 % del total que confirma el pedido."],
         ["FOB", "Precio de la mercadería puesta a bordo del barco en el puerto argentino. El flete y el seguro los paga el comprador."],
         ["CIF", "Precio que incluye mercadería, flete marítimo y seguro hasta el puerto de destino."],
         ["Incoterm", "Regla internacional que dice quién paga y quién se hace cargo de cada tramo del viaje."],
         ["Carta de crédito", "Compromiso de un banco de pagar al exportador cuando presenta los documentos correctos."],
         ["B/L (conocimiento de embarque)", "Documento de la naviera que prueba que la carga fue embarcada."],
         ["Registro comercial / licencia comercial", "Número oficial que acredita que una empresa existe legalmente en su país."],
         ["Halal", "Certificación de que un producto cumple las normas alimentarias del islam."]],
        widths=[4.5, 12], size=10.5, bold_first_col=True)

D.h1("13. Contacto")
D.p("WhatsApp: +54 9 11 3348-6017 (en Argentina, 11 3348-6017)", bold=True)
D.p("Rumbo Global Consultores S.A. · Buenos Aires, Argentina")
D.save("06_Documentos/Manual_de_Usuario_ASHAB.docx")
print("manual ok")
