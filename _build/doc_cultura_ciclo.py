import sys
sys.path.insert(0, "_build")
from dx import Doc

# =================== CULTURA ===================
D = Doc("rg", "Rumbo Global Consultores S.A. · Cultura de la consultora")
D.cover("Cultura de la consultora", "Cómo pensamos, cómo trabajamos y cómo nos tratamos",
        ["Rumbo Global Consultores S.A.", "Versión 1.0 · 2026"], logo="01_Logo/logo_consultora.png", logo_w=10)

D.h1("1. Quiénes somos")
D.p("Rumbo Global Consultores S.A. es una sociedad anónima argentina que ayuda a pymes y economías regionales a vender al mundo. Acompañamos todo el camino: elegir el mercado, preparar el producto, resolver la documentación, coordinar el envío y cobrar.")
D.p("Nuestro primer proyecto de gran escala es ASHAB, una marca de yerba mate argentina para Siria, Líbano, Jordania y Emiratos Árabes Unidos. Trabajar con mercados de otra cultura e idioma define buena parte de lo que somos.")

D.h1("2. Misión, visión y propósito")
D.table([["Misión", "Hacer que exportar sea un proceso claro, ordenado y rentable para empresas que nunca lo hicieron."],
         ["Visión", "Ser la consultora de referencia en comercio exterior para las economías regionales de Argentina."],
         ["Propósito", "Que un productor de Misiones, Mendoza o Tucumán pueda ver su marca en una góndola del otro lado del mundo."]],
        widths=[3, 13.5], size=11, header=False, bold_first_col=True)

D.h1("3. Nuestros valores en la práctica")
D.p("Un valor que no cambia lo que hacemos cada día no sirve. Por eso cada uno viene con lo que hacemos y lo que no hacemos.")
D.table([["Valor", "Lo que hacemos", "Lo que no hacemos"],
         ["Rumbo claro", "Cada proyecto tiene objetivo, fecha y responsable por escrito.", "Empezar sin saber qué resultado se espera."],
         ["Palabra cumplida", "Avisamos con tiempo si algo se va a demorar y proponemos una solución.", "Prometer plazos que sabemos que no se cumplen."],
         ["Respeto cultural", "Estudiamos costumbres, religión, idioma y calendario del país antes de contactar a un comprador.", "Aplicar a Medio Oriente las reglas de negocio de Argentina."],
         ["Datos, no opiniones", "Decidimos con cifras, fuentes y supuestos explícitos.", "Presentar un número sin decir de dónde sale."],
         ["Seguridad primero", "Cuidamos a las personas, la mercadería y la información del cliente.", "Saltear un control para ganar tiempo."],
         ["Mejora continua", "Cerramos cada proyecto con lecciones aprendidas y las usamos en el siguiente.", "Repetir un error ya conocido."]],
        widths=[3.2, 7, 6.3], size=10, bold_first_col=True)

D.h1("4. Cómo trabajamos juntos")
D.bullets([("Comunicación directa. ", "Cada proyecto tiene un grupo de WhatsApp con el cliente y un canal interno. Respondemos en el día hábil."),
           ("Reunión semanal de 30 minutos ", "por proyecto: qué se hizo, qué sigue y qué nos frena."),
           ("Todo por escrito. ", "Lo que se acuerda por llamada se confirma por mensaje o correo el mismo día."),
           ("Un responsable por tarea. ", "Si algo tiene dos responsables, no tiene ninguno."),
           ("Se pregunta sin miedo. ", "Es mejor una pregunta de más que un embarque observado en la aduana.")])

D.h1("5. Código de conducta")
D.h2("5.1 Ética y anticorrupción")
D.bullets(["No ofrecemos ni aceptamos pagos indebidos a funcionarios, aduaneros, inspectores ni compradores, en Argentina o en el exterior.",
           "Los honorarios de agentes y gestores se pagan contra factura y servicio concreto.",
           "Los regalos se limitan a cortesías de poco valor y se informan al responsable del proyecto.",
           "Cumplimos las normas de prevención del lavado de activos y las sanciones internacionales vigentes. Antes de operar con un comprador verificamos quién es."])
D.h2("5.2 Conflictos de interés")
D.p("Si una persona del equipo tiene un vínculo familiar, societario o económico con un cliente, proveedor o comprador, lo informa antes de participar de la decisión.")
D.h2("5.3 Confidencialidad")
D.p("La información del cliente (costos, precios, compradores, planos de planta) es confidencial. Se comparte solo con quien la necesita para trabajar, se guarda con contraseña y se devuelve o elimina al terminar el proyecto.")
D.h2("5.4 Trato respetuoso")
D.p("No toleramos el acoso, la discriminación ni las bromas que humillan. Cualquier persona puede hacer una denuncia a la Dirección General de forma confidencial y no sufrirá represalias.")

D.h1("6. Trabajar con la cultura de cada mercado")
D.p("Trabajamos con cualquier país del mundo. Nuestro caso actual es Medio Oriente y por eso la tabla toma ese ejemplo, pero las mismas reglas valen para Europa, Asia, África o América: estudiar la cultura del mercado antes de contactar al comprador.")
D.table([["Tema", "Buena práctica"],
         ["Relaciones", "El negocio empieza por la confianza personal. Se dedica tiempo a conocerse antes de hablar de precios."],
         ["Idioma", "Materiales comerciales en árabe e inglés. Los textos en árabe los revisa un hablante nativo."],
         ["Calendario", "Se respeta el viernes como día de descanso en muchos países y el mes de Ramadán, con horarios y ritmos distintos. Los embarques y reuniones se planifican con ese calendario."],
         ["Producto", "Etiquetas claras y, cuando corresponde, certificación halal. No se usan imágenes o textos que puedan ofender."],
         ["Trato", "Saludo cordial, paciencia en la negociación y cumplimiento estricto de lo acordado."]],
        widths=[3.2, 13.3], size=10, bold_first_col=True)
D.callout("Regla simple", "Ante la duda sobre una costumbre, preguntamos a un contacto local antes de actuar.", "info")

D.h1("7. Equipo, formación y crecimiento")
D.bullets(["Capacitación anual en comercio exterior, normativa aduanera y seguros de carga.",
           "Cursos de inglés y de árabe básico para quien trabaje con Medio Oriente.",
           "Rotación entre áreas (comercio exterior, logística, legal, tecnología) para entender el proceso completo.",
           "Cada persona tiene un plan de desarrollo que se revisa dos veces por año."])

D.h1("8. Modalidad de trabajo")
D.bullets(["Esquema híbrido: presencial en reuniones clave y trabajo remoto el resto.",
           "Horario base de 9 a 18 con flexibilidad. Durante embarques críticos hay guardia rotativa acordada con anticipación y compensada.",
           "Descansos respetados: no se esperan respuestas fuera de horario salvo urgencias de embarque.",
           "Puesto de trabajo seguro: silla y pantalla regulables, pausas activas cada dos horas y botiquín disponible."])

D.h1("9. Rituales")
D.table([["Ritual", "Cuándo", "Para qué"],
         ["Kick-off del proyecto", "Al firmar el contrato", "Alinear objetivo, equipo y fechas con el cliente."],
         ["Reunión semanal", "Todas las semanas", "Seguir avances y destrabar problemas."],
         ["Revisión mensual de resultados", "Fin de mes", "Mirar indicadores y decidir ajustes."],
         ["Cierre con lecciones aprendidas", "Al terminar cada proyecto", "Registrar qué funcionó y qué no."],
         ["Mate de los viernes", "Cada viernes", "Compartir un mate (con yerba de nuestros clientes) y conocernos mejor."]],
        widths=[5, 4, 7.5], size=10, bold_first_col=True)

D.h1("10. Cómo medimos nuestra cultura")
D.table([["Indicador", "Meta"],
         ["Proyectos entregados en fecha", "90 % o más"],
         ["Satisfacción del cliente (encuesta al cierre)", "4,5 sobre 5 o más"],
         ["Clima laboral (encuesta anual anónima)", "80 % o más de respuestas positivas"],
         ["Capacitación por persona", "40 horas por año"],
         ["Incidentes de ética o confidencialidad", "Cero"]],
        widths=[11, 5.5], size=10.5, bold_first_col=True)
D.save("06_Documentos/Cultura_de_la_Consultora.docx")
print("cultura ok")

# =================== CICLO DE TRABAJO ===================
D = Doc("rg", "Rumbo Global Consultores S.A. · Ciclo de trabajo")
D.cover("Ciclo de trabajo", "Las 8 etapas con las que llevamos adelante cada proyecto",
        ["Rumbo Global Consultores S.A.", "Versión 1.0 · 2026", "Aplicado al proyecto ASHAB"], logo="01_Logo/logo_consultora.png", logo_w=10)

D.h1("1. Visión general")
D.p("Todos los proyectos de Rumbo Global siguen el mismo ciclo de ocho etapas. Tiene dos puntos de control, donde el proyecto no avanza si no se cumplen ciertas condiciones, y termina volviendo al principio para mejorar.")
D.image("03_Diagramas/S3_como_trabaja_la_consultora_simple.png", 16.5, "Figura 1. El ciclo explicado simple.")
D.image("03_Diagramas/C5_ciclo_de_trabajo_consultora.png", 16.5, "Figura 2. El ciclo completo, con sus puntos de control y vueltas atrás.")

D.h1("2. Las ocho etapas")
D.table([["N.º", "Etapa", "Qué se hace", "Entregable", "Duración típica"],
         ["1", "Contacto y brief", "Primera reunión, conocer producto, capacidad y objetivos del cliente.", "Brief firmado", "1 semana"],
         ["2", "Diagnóstico y mercado", "Estudio de demanda, competencia, requisitos sanitarios y aduaneros; selección de países.", "Informe de mercado", "3 a 4 semanas"],
         ["3", "Propuesta y contrato", "Alcance, cronograma, presupuesto y honorarios.", "Propuesta y contrato firmados", "1 a 2 semanas"],
         ["4", "Planificación", "Plan detallado, responsables, hitos y riesgos.", "Plan de proyecto", "2 semanas"],
         ["5", "Ejecución", "Marca, habilitaciones, compradores, contratos, logística y cobranza.", "Entregables por fase", "6 a 10 meses"],
         ["6", "Control", "Seguimiento semanal de avances, costos y riesgos; reportes mensuales.", "Informe mensual", "Continuo"],
         ["7", "Cierre", "Evaluación de resultados, entrega de documentación y lecciones aprendidas.", "Informe final", "2 semanas"],
         ["8", "Mejora continua", "Nuevos mercados, nuevos productos y ajustes al método.", "Plan de mejoras", "Continuo"]],
        widths=[1.1, 3, 6.2, 3.4, 2.8], size=9.5, bold_first_col=True)

D.h1("3. Puntos de control")
D.table([["Punto de control", "Dónde está", "Se avanza si…", "Si no se cumple…"],
         ["A. Aprobación del cliente", "Después de la propuesta (etapa 3)", "El cliente aprueba alcance, presupuesto y plazos por escrito.", "Se ajusta la propuesta y se vuelve a presentar."],
         ["B. Verificación de objetivos", "Después del control (etapa 6)", "Se cumplen los objetivos de la fase: fechas, costos y calidad dentro de lo acordado.", "Se corrige la ejecución y se vuelve a controlar."]],
        widths=[3.5, 3.8, 5.2, 4], size=10, bold_first_col=True)
D.p("Además, durante la ejecución del proyecto de exportación hay controles propios del negocio: aprobación de laboratorio y certificados, aceptación de condiciones por el importador, documentación conforme en la aduana y aprobación sanitaria en destino. Están en los diagramas de flujo complejos 1 a 3.")

D.h1("4. Roles y responsabilidades")
D.p("R = responsable de hacerlo · A = aprueba · C = se consulta · I = se informa.")
D.table([["Actividad", "Cliente", "Líder de proyecto", "Comercio exterior", "Logística y aduana", "Legal", "Tecnología"],
         ["Brief y diagnóstico", "C", "A", "R", "I", "I", "I"],
         ["Propuesta y contrato", "A", "R", "C", "I", "R", "I"],
         ["Marca y habilitaciones", "C", "A", "I", "I", "R", "I"],
         ["Búsqueda y negociación con compradores", "C", "A", "R", "I", "C", "I"],
         ["Documentos y embarque", "I", "A", "C", "R", "C", "I"],
         ["Sitio web y sistema", "C", "A", "I", "I", "C", "R"],
         ["Cobranza y cierre", "I", "A", "C", "R", "C", "I"]],
        widths=[5.2, 1.6, 2.2, 2.2, 2.2, 1.6, 1.8], size=9.5, bold_first_col=True)

D.h1("5. Cronograma tipo: proyecto ASHAB, 12 meses")
meses = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"]
fases = [("Diagnóstico y plan", 1, 3), ("Marca y habilitaciones", 2, 5), ("Prospección y feria", 4, 8), ("Primer embarque", 6, 9), ("Escala y seguimiento", 9, 12)]
t = D.table([["Fase"] + meses] + [[n] + ["" for _ in meses] for n, a, b in fases], widths=[4.4] + [1.0] * 12, size=9, header=True, zebra=False, bold_first_col=True)
from dx import shade
for i, (n, a, b) in enumerate(fases, 1):
    for m in range(a, b + 1):
        shade(t.cell(i, m), "D0021B")
D.p("Cada barra roja indica los meses en que la fase está activa.", italic=True, size=9.5)

D.h1("6. Reuniones y reportes")
D.table([["Instancia", "Frecuencia", "Participan", "Resultado"],
         ["Reunión de seguimiento", "Semanal (30 min)", "Líder de proyecto y cliente", "Acta corta con acuerdos"],
         ["Informe mensual", "Mensual", "Dirección, líder y cliente", "Avance, costos, riesgos y decisiones"],
         ["Comité de riesgos", "Cada embarque", "Logística, legal y líder", "Plan de contingencia aprobado"],
         ["Cierre de proyecto", "Al finalizar", "Todo el equipo", "Lecciones aprendidas"]],
        widths=[4.3, 3.5, 4.5, 4.2], size=10, bold_first_col=True)

D.h1("7. Gestión de cambios y riesgos")
D.numbered(["Cualquier cambio de alcance, plazo o costo se pide por escrito.", "El líder de proyecto evalúa el impacto en una hoja con costo, tiempo y riesgo.",
            "El cliente aprueba o rechaza antes de ejecutar.", "El cambio se registra en el plan.", "Los riesgos nuevos se anotan en la matriz y se revisan en la reunión semanal."])

D.h1("8. Indicadores del ciclo")
D.table([["Indicador", "Cómo se mide", "Meta"],
         ["Cumplimiento de hitos", "Hitos entregados en fecha ÷ hitos totales", "90 % o más"],
         ["Desvío de presupuesto", "(Costo real − presupuesto) ÷ presupuesto", "Menos de 10 %"],
         ["Documentación sin observaciones", "Embarques sin observaciones de aduana ÷ embarques totales", "95 % o más"],
         ["Cobranza en término", "Embarques cobrados en el plazo acordado ÷ embarques", "95 % o más"],
         ["Satisfacción del cliente", "Encuesta al cierre (1 a 5)", "4,5 o más"]],
        widths=[5, 8, 3.5], size=10, bold_first_col=True)

D.h1("9. Herramientas")
D.bullets(["WhatsApp (11 3348-6017): canal rápido con el cliente.", "Sitios web de la consultora y del proyecto con ingreso seguro (DNI o Google): estado de proyectos y pedidos.",
           "Planilla de costos en Excel: presupuesto, escenarios y seguimiento.", "Diagramas de flujo: guía visual de cada fase."])
D.save("06_Documentos/Ciclo_de_Trabajo.docx")
print("ciclo ok")
