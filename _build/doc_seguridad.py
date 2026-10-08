import sys
sys.path.insert(0, "_build")
from dx import Doc

D = Doc("ash", "Seguridad e higiene · Cliente ASHAB")
D.cover("Seguridad e higiene del cliente", "Plan para la planta yerbatera y el embarque de ASHAB",
        ["Preparado por Rumbo Global Consultores S.A.", "Versión 1.0 · 2026",
         "Documento orientativo: debe validarlo el profesional matriculado en Higiene y Seguridad de la empresa."])

D.h1("1. Objetivo y alcance")
D.p("Este plan reúne las medidas básicas para que la yerbatera cliente trabaje de forma segura para las personas y entregue un producto inocuo, como exigen los compradores de Medio Oriente y las autoridades de Argentina. Cubre desde la recepción de la hoja verde hasta la carga del contenedor, y también las visitas del equipo de la consultora a la planta.")
D.callout("Alcance de este documento", "Es una guía de trabajo preparada por la consultora. No reemplaza al servicio de Higiene y Seguridad en el Trabajo ni al responsable técnico de calidad de la empresa, que deben adaptarlo a la planta real, medir y firmar los registros.", "warn")

D.h1("2. Marco normativo de referencia")
D.table([["Norma", "Para qué se aplica"],
         ["Ley 19.587 de Higiene y Seguridad en el Trabajo y Decreto 351/79", "Condiciones generales de seguridad: edificios, instalaciones, ruido, iluminación, incendios, EPP."],
         ["Ley 24.557 de Riesgos del Trabajo", "Prevención de accidentes y cobertura de la ART."],
         ["Resolución SRT 295/03", "Ergonomía y manejo manual de cargas."],
         ["Código Alimentario Argentino (CAA)", "Requisitos del producto y buenas prácticas de manufactura (BPM). Aplica al registro del establecimiento y del producto."],
         ["Requisitos de SENASA y autoridad sanitaria", "Habilitación del establecimiento y certificados de exportación."],
         ["Requisitos del comprador y del país de destino", "Etiquetado, certificados y, si se contrata, certificación halal."]],
        widths=[6.5, 10], size=10, bold_first_col=True)
D.p("Antes de implementar el plan, el responsable de Higiene y Seguridad debe confirmar la versión vigente de cada norma y las resoluciones complementarias de su jurisdicción.", italic=True, size=10)

D.h1("3. Proceso productivo y riesgos principales")
D.p("La yerba mate pasa por estas etapas. En cada una hay riesgos distintos para las personas y para la inocuidad:")
D.numbered(["Recepción de hoja verde", "Sapecado (golpe de calor)", "Secado", "Canchado (molienda gruesa)", "Estacionamiento", "Molienda y zarandeo", "Mezcla y empaque", "Depósito", "Carga del contenedor"])
D.h2("3.1 Matriz de riesgos por etapa")
D.table([["Etapa", "Peligro", "Medidas de control", "EPP"],
         ["Recepción", "Atropello, caída de bolsas y camiones en maniobra", "Circulación peatonal separada, señalización, chaleco reflectivo, personal de maniobra", "Chaleco, calzado de seguridad, guantes"],
         ["Sapecado y secado", "Calor, quemaduras, incendio, monóxido de carbono, humo", "Protección de partes calientes, ventilación, detector de CO, extintores, mantenimiento del horno", "Guantes térmicos, calzado, protección respiratoria"],
         ["Canchado y molienda", "Ruido, polvo, atrapamiento en máquinas, explosión de polvo", "Protecciones fijas, bloqueo y etiquetado (LOTO), aspiración de polvo, conexión a tierra", "Protección auditiva, barbijo, anteojos"],
         ["Estacionamiento y depósito", "Caída de pilas, incendio, humedad y plagas", "Estibas estables, alturas máximas, orden y limpieza, control de humedad y plagas", "Calzado, guantes"],
         ["Zarandeo y mezcla", "Polvo, ruido, cortes, contaminación con metales", "Aspiración, imanes y detector de metales, mantenimiento", "Auditiva, barbijo, cofia"],
         ["Empaque", "Movimientos repetitivos, cargas manuales, contaminación cruzada", "Rotación de tareas, pausas, mesas regulables, higiene de manos", "Cofia, barbijo, guantes descartables, delantal"],
         ["Carga del contenedor", "Sobreesfuerzo, caídas, golpes con autoelevador, calor en el contenedor", "Rampas y plataformas, pallets, operadores habilitados, hidratación y pausas", "Calzado, guantes, chaleco"]],
        widths=[2.8, 4.2, 6.2, 3.3], size=9, bold_first_col=True)

D.h1("4. Equipos de protección personal (EPP)")
D.bullets(["La empresa entrega los EPP sin costo, registra la entrega con firma y los reemplaza cuando se gastan.",
           "Los EPP deben ser certificados y del talle adecuado.",
           "Capacitación en uso, guardado y limpieza.",
           "Nadie ingresa a un sector sin el EPP indicado en la señalización de ingreso."])
D.table([["Puesto", "EPP mínimo"],
         ["Secadero y horno", "Guantes térmicos, calzado de seguridad, protección respiratoria, ropa de algodón"],
         ["Molienda y zarandeo", "Protección auditiva, barbijo, anteojos, calzado de seguridad"],
         ["Empaque", "Cofia, barbijo, guantes descartables, delantal lavable, calzado antideslizante"],
         ["Depósito y carga", "Calzado de seguridad, guantes, chaleco reflectivo, faja o apoyo lumbar si se indica"],
         ["Mantenimiento", "Anteojos, guantes, protección auditiva, calzado, bloqueo y etiquetado"],
         ["Visitas", "Cofia, barbijo, guardapolvo, calzado cerrado, casco en zona de carga"]],
        widths=[4, 12.5], size=10, bold_first_col=True)

D.h1("5. Higiene e inocuidad del producto")
D.h2("5.1 Personal")
D.bullets(["Lavado y desinfección de manos al entrar, después de usar el baño y después de tocar basura.", "Uniforme limpio y exclusivo para la planta. Cabello cubierto, uñas cortas y sin esmalte, sin joyas.",
           "Prohibido comer, fumar o escupir en zonas de producción.", "Quien tenga heridas abiertas o enfermedad contagiosa no manipula alimentos hasta recibir el alta.", "Control de salud anual con libreta sanitaria vigente."])
D.h2("5.2 Instalaciones y equipos")
D.bullets(["Pisos, paredes y techos lisos, limpios y sin grietas.", "Equipos de acero inoxidable o materiales aptos para alimentos.", "Iluminación suficiente y con protección contra rotura.", "Vestuarios y baños separados de producción, con jabón, toallas descartables y cartelería de lavado."])
D.h2("5.3 Limpieza, plagas y residuos")
D.bullets(["Programa de limpieza por escrito: qué, cómo, con qué producto, con qué frecuencia y quién.", "Control integrado de plagas por empresa habilitada, con mapa de cebaderas y registros.",
           "Residuos en tachos con tapa, retiro diario y área de acopio alejada de producción.", "Agua potable con análisis periódicos."])
D.h2("5.4 Trazabilidad y puntos críticos")
D.bullets(["Cada lote lleva un código que permite saber qué hoja, qué fecha de elaboración y a qué cliente fue.", "Puntos críticos de control sugeridos: temperatura y tiempo de secado, humedad final del producto, detección de metales y estado del envase.",
           "Plan de retiro de producto del mercado: quién decide, cómo se avisa al importador y cómo se recupera la mercadería.",
           "Análisis de laboratorio por lote o por embarque exigido por el comprador (humedad, contaminantes y microbiología según corresponda)."])

D.h1("6. Prevención de incendio y explosión de polvo")
D.p("La yerba molida produce polvo fino que, en el aire y ante una chispa, puede arder rápido. Los secaderos con fuego directo son otra fuente de ignición.")
D.bullets(["Limpiar el polvo acumulado en estructuras, techos y equipos con aspiración, no con aire comprimido.", "Instalar aspiración localizada en molinos y zarandas.",
           "Conectar a tierra equipos y cañerías y usar motores e instalaciones eléctricas adecuadas.", "Mantener extintores cargados, señalizados y a menos de 20 metros de cualquier puesto, con revisión anual.",
           "Hidrantes o red de incendio según el cálculo de carga de fuego del profesional.", "Plan de evacuación con salidas libres, luces de emergencia y puntos de reunión. Simulacro al menos una vez por año.",
           "Brigada de emergencia entrenada en cada turno."])

D.h1("7. Ergonomía y manejo de cargas")
D.bullets(["Evaluar cada puesto con la Resolución SRT 295/03. Como referencia práctica, evitar levantar a mano cargas mayores de 25 kg y reducir peso y frecuencia siempre que se pueda.",
           "Las cajas de 10 a 12 kg se mueven con pallets, zorras y cintas. Evitar levantar por encima de los hombros o por debajo de las rodillas.",
           "Rotación de tareas y pausas activas cada dos horas en empaque y carga.",
           "Capacitar en técnica de levante: espalda recta, carga cerca del cuerpo y piernas flexionadas."])

D.h1("8. Carga de contenedores y transporte")
D.numbered(["Inspeccionar el contenedor antes de cargar: sin olores, sin humedad, sin agujeros, piso limpio y seco. Registrar con fotos.", "Si el contenedor no está en condiciones, rechazarlo y pedir otro. Nunca cargar yerba en un contenedor con olor a químicos o combustible.",
            "Estibar de forma estable, sin sobrepasar el peso máximo ni dejar huecos que permitan movimientos.", "Usar silica gel o papel kraft según indique el plan de transporte para reducir la humedad.",
            "Precintar con precinto numerado, fotografiar y anotar el número en los documentos.", "Autoelevadores solo con operadores habilitados y con registro de mantenimiento.", "Circulación de camiones con prioridad vial y personal de maniobra."])

D.h1("9. Emergencias y primeros auxilios")
D.bullets(["Botiquín completo en cada sector, controlado cada mes.", "Personal con curso de primeros auxilios y RCP en cada turno.", "Teléfonos de emergencia visibles: emergencias 911, bomberos 100, emergencias médicas 107, ART y centro de salud más cercano.",
           "Registro e investigación de todo accidente o casi accidente, con medidas correctivas.", "Duchas y lavaojos cerca de los sectores donde se manipulan productos de limpieza."])

D.h1("10. Capacitación y registros")
D.table([["Tema", "A quién", "Frecuencia"],
         ["Inducción de seguridad e higiene", "Personal nuevo", "Antes de empezar"],
         ["Buenas prácticas de manufactura", "Todo el personal", "Dos veces por año"],
         ["Uso de EPP y bloqueo de máquinas", "Producción y mantenimiento", "Anual"],
         ["Prevención de incendio y evacuación", "Todo el personal", "Anual, con simulacro"],
         ["Manejo manual de cargas", "Empaque, depósito y carga", "Anual"],
         ["Primeros auxilios", "Brigada y supervisores", "Anual"]],
        widths=[7, 5.5, 4], size=10, bold_first_col=True)
D.p("Cada capacitación deja constancia con fecha, tema, duración, asistentes firmados y nombre del instructor.")

D.h1("11. Visitas de la consultora a la planta")
D.bullets(["Avisar la visita con anticipación y completar el registro de ingreso.", "Usar el mismo EPP y vestimenta que el personal; no tocar equipos ni producto sin autorización.", "No ingresar con enfermedad respiratoria o gastrointestinal.",
           "Recorrer acompañado por una persona de la planta y respetar la señalización.", "Las fotos se toman solo con autorización y no se comparten fuera del proyecto."])

D.h1("12. Plan de implementación en 90 días")
D.table([["Plazo", "Acción", "Responsable"],
         ["Días 1 a 15", "Relevamiento de riesgos por puesto y de condiciones de higiene. Revisar habilitaciones y análisis vigentes.", "Higiene y Seguridad, Calidad"],
         ["Días 16 a 45", "Compra y entrega de EPP, señalización, extintores y botiquines. Programa de limpieza y control de plagas.", "Gerencia, Compras"],
         ["Días 46 a 75", "Capacitaciones, simulacro de evacuación y puesta en marcha de registros de trazabilidad.", "Higiene y Seguridad, Producción"],
         ["Días 76 a 90", "Auditoría interna con la lista de control, correcciones y aprobación de la gerencia.", "Calidad, Gerencia"]],
        widths=[2.8, 10, 3.7], size=10, bold_first_col=True)

D.h1("13. Lista de control mensual")
D.table([["Ítem", "Cumple", "No cumple", "Observaciones"],
         ["EPP entregados y en buen estado", "☐", "☐", ""],
         ["Salidas de emergencia libres y señalizadas", "☐", "☐", ""],
         ["Extintores cargados y con fecha vigente", "☐", "☐", ""],
         ["Polvo acumulado controlado en molinos y zarandas", "☐", "☐", ""],
         ["Protecciones de máquinas colocadas", "☐", "☐", ""],
         ["Baños y vestuarios limpios, con jabón y toallas", "☐", "☐", ""],
         ["Control de plagas al día", "☐", "☐", ""],
         ["Registros de limpieza completos", "☐", "☐", ""],
         ["Lote identificado y trazable", "☐", "☐", ""],
         ["Contenedor inspeccionado y fotografiado", "☐", "☐", ""],
         ["Capacitaciones del mes realizadas", "☐", "☐", ""],
         ["Botiquín completo", "☐", "☐", ""]],
        widths=[8.5, 1.8, 2.2, 4], size=10)

D.h1("14. Indicadores")
D.table([["Indicador", "Cómo se calcula", "Meta"],
         ["Índice de frecuencia", "Accidentes con baja × 1.000.000 ÷ horas trabajadas", "Bajar cada año"],
         ["Accidentes y casi accidentes", "Cantidad registrada por mes", "Cero accidentes graves"],
         ["Capacitación cumplida", "Capacitaciones realizadas ÷ planificadas", "100 %"],
         ["No conformidades de higiene cerradas", "Cerradas en plazo ÷ abiertas", "90 % o más"],
         ["Reclamos de calidad de compradores", "Cantidad por embarque", "Cero"]],
        widths=[5.2, 8, 3.3], size=10, bold_first_col=True)
D.save("06_Documentos/Seguridad_e_Higiene_del_Cliente.docx")
print("seguridad ok")
