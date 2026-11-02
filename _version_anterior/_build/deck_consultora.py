import sys
sys.path.insert(0, "_build")
from pp import Deck, WARN

T = dict(dark="111111", light="FFFFFF", ink="111111", on_dark="FFFFFF", head="Arial", body="Calibri")
RED, SOFT, MUT, GRAY = "D0021B", "F3F3F3", "555555", "BDBDBD"
d = Deck(T)

# 1 Portada
s = d.slide(dark=True, notes="Presentación institucional de Rumbo Global Consultores S.A.")
d.text(s, 0.7, 1.5, 7.5, 1.0, [("RUMBO GLOBAL", {"size": 54, "bold": True, "font": "Arial"})], size=54, check=False, color="FFFFFF")
d.text(s, 0.7, 2.55, 7.0, 0.5, "CONSULTORES S.A.", size=22, bold=True, color=RED, font="Arial")
d.text(s, 0.7, 3.6, 7.2, 1.6, [("Presentación institucional", {"size": 32, "bold": True, "font": "Arial"}),
                               ("Consultoría en comercio exterior para pymes argentinas", {"size": 20, "color": "D9D9D9"})], size=32)
d.text(s, 0.7, 6.5, 6, 0.4, "Buenos Aires, Argentina · 2026", size=14, color="9A9A9A")
d.image(s, "_build/isotipo_dark.png", 8.6, 1.35, w=4.2, alt="Isotipo de Rumbo Global: globo terráqueo con un avión")

# 2 Quiénes somos
s = d.slide(title="Quiénes somos", notes="Sociedad anónima argentina especializada en abrir mercados externos para pymes y economías regionales.")
d.text(s, 0.7, 1.7, 6.4, 4.8, [
    ("Rumbo Global Consultores S.A. es una sociedad anónima argentina que acompaña a pymes en todo el camino de la exportación: desde elegir el mercado hasta cobrar el embarque.", {"size": 18, "gap": 14}),
    ("Misión", {"size": 20, "bold": True, "color": RED, "gap": 2}),
    ("Hacer que exportar sea un proceso claro, ordenado y rentable para empresas que nunca lo hicieron.", {"size": 16, "gap": 12}),
    ("Visión", {"size": 20, "bold": True, "color": RED, "gap": 2}),
    ("Ser la consultora de referencia en comercio exterior para las economías regionales de Argentina.", {"size": 16}),
], size=16)
for i, (n, l) in enumerate([("Mundo", "sin límite de países: cada proyecto elige su mercado"), ("8", "etapas en nuestro ciclo de trabajo"), ("8", "idiomas en nuestro sitio web")]):
    y = 1.7 + i * 1.65
    d.rect(s, 7.7, y, 4.9, 1.45, fill=SOFT)
    d.text(s, 7.9, y + 0.1, 1.5, 1.25, n, size=54 if len(n) < 3 else 24, bold=True, color=RED, font="Arial", anchor="m", check=False)
    d.text(s, 9.4, y + 0.1, 3.1, 1.25, l, size=16, anchor="m")

# 3 Qué hacemos
s = d.slide(title="Qué hacemos", notes="Seis servicios que pueden contratarse juntos o por separado.")
sv = [("Diagnóstico y mercado", "Analizamos producto, capacidad y competencia, y elegimos países y canales."),
      ("Plan de exportación", "Objetivos, presupuesto, cronograma, precios y estrategia de marca."),
      ("Habilitaciones y marca", "Registros ante ARCA y organismos sanitarios, marca y certificaciones."),
      ("Compradores y contratos", "Importadores, muestras, ferias y contratos de compraventa."),
      ("Logística y cobranza", "Contenedor, seguro, despacho y cobro con carta de crédito."),
      ("Presencia digital", "Sitios web en varios idiomas, acceso de clientes y WhatsApp.")]
for i, (t, desc) in enumerate(sv):
    c, r = i % 3, i // 3
    x, y = 0.7 + c * 4.05, 1.75 + r * 2.55
    d.rect(s, x, y, 3.8, 2.3, fill=SOFT)
    d.num(s, x + 0.25, y + 0.25, 0.55, i + 1, RED, size=18)
    d.text(s, x + 0.25, y + 0.95, 3.3, 0.45, t, size=19, bold=True, font="Arial")
    d.text(s, x + 0.25, y + 1.4, 3.3, 0.85, desc, size=14, color=MUT)

# 4 Cómo trabajamos
s = d.slide(title="Cómo trabajamos", notes="Cuatro pasos simples para el cliente; por detrás hay un ciclo de ocho etapas con puntos de control.")
d.image(s, "03_Diagramas/S3_como_trabaja_la_consultora_simple.png", 0.7, 1.55, w=11.9, alt="Cuatro pasos: escuchamos, analizamos, armamos un plan, ejecutamos y controlamos")
d.text(s, 0.7, 4.35, 11.9, 0.4, "Por detrás, un ciclo de 8 etapas con puntos de control", size=18, bold=True, font="Arial")
et = ["Contacto y brief", "Diagnóstico", "Propuesta y contrato", "Planificación", "Ejecución", "Control", "Cierre", "Mejora continua"]
for i, e in enumerate(et):
    c, r = i % 4, i // 4
    x, y = 0.7 + c * 3.0, 4.95 + r * 0.95
    d.rect(s, x, y, 2.85, 0.8, fill=None, line="111111", lw=1.5)
    d.num(s, x + 0.12, y + 0.14, 0.52, i + 1, RED, size=16)
    d.text(s, x + 0.72, y + 0.05, 2.05, 0.7, e, size=15, bold=True, anchor="m")

# 5 Cultura
s = d.slide(dark=True, title="Nuestra cultura", notes="Seis valores que guían las decisiones diarias.")
vals = [("Rumbo claro", "Cada proyecto tiene objetivo, fecha y responsable."),
        ("Palabra cumplida", "Decimos lo que vamos a hacer y lo hacemos."),
        ("Respeto cultural", "Entendemos costumbres, idioma y religión del destino."),
        ("Datos, no opiniones", "Decidimos con números, no con intuiciones."),
        ("Seguridad primero", "Cuidamos personas, mercadería e información."),
        ("Mejora continua", "Cada proyecto cierra con lecciones aprendidas.")]
for i, (t, desc) in enumerate(vals):
    c, r = i % 3, i // 3
    x, y = 0.7 + c * 4.05, 1.75 + r * 2.55
    d.rect(s, x, y, 3.8, 2.3, fill="222222")
    d.rect(s, x + 0.25, y + 0.28, 0.32, 0.32, fill=RED, shape="oval")
    d.text(s, x + 0.25, y + 0.8, 3.3, 0.5, t, size=20, bold=True, font="Arial")
    d.text(s, x + 0.25, y + 1.35, 3.3, 0.85, desc, size=15, color="D0D0D0")

# 6 Organización
s = d.slide(title="Equipo y organización", notes="Áreas de la consultora. Cada proyecto tiene líder, responsable de documentación y responsable de calidad.")
d.rect(s, 4.9, 1.65, 3.5, 0.95, fill="111111")
d.text(s, 4.9, 1.65, 3.5, 0.95, "Dirección General", size=20, bold=True, color="FFFFFF", align="c", anchor="m", font="Arial")
d.rect(s, 6.62, 2.6, 0.06, 0.45, fill="111111", shape="rect")
d.rect(s, 1.95, 3.03, 9.43, 0.06, fill="111111", shape="rect")
areas = [("Comercio exterior", "Mercados, precios, compradores y contratos"), ("Logística y aduana", "Contenedores, documentación, seguros y despacho"),
         ("Legal y compliance", "Contratos, marcas, ética y normativa"), ("Tecnología y datos", "Sitios web, sistemas y análisis")]
for i, (t, desc) in enumerate(areas):
    x = 0.7 + i * 3.05
    d.rect(s, x + 1.2, 3.03, 0.06, 0.4, fill="111111", shape="rect")
    d.rect(s, x, 3.43, 2.85, 2.1, fill=SOFT)
    d.text(s, x + 0.15, 3.55, 2.55, 0.5, t, size=18, bold=True, font="Arial", color=RED)
    d.text(s, x + 0.15, 4.15, 2.55, 1.3, desc, size=15, color=MUT)
d.text(s, 0.7, 5.95, 11.9, 0.9, "Cada proyecto tiene un líder, un responsable de documentación y un responsable de calidad, más un canal directo con el cliente por WhatsApp.", size=16, color="111111")

# 7 Caso ASHAB
s = d.slide(title="Caso en curso: proyecto ASHAB", notes="Cliente: yerbatera de Misiones. Mercado: Siria, Líbano, Jordania y Emiratos Árabes Unidos.")
d.text(s, 0.7, 1.7, 6.3, 4.9, [
    ("Una yerbatera de Misiones quiere exportar yerba mate con marca propia a Medio Oriente.", {"size": 22, "gap": 18}),
    ("Qué hacemos por el cliente", {"size": 22, "bold": True, "color": RED, "gap": 8}),
    ("Elegimos países y canales según la demanda real", {"size": 18, "gap": 10}),
    ("Creamos la marca ASHAB y el packaging en tres idiomas", {"size": 18, "gap": 10}),
    ("Gestionamos habilitaciones, documentos y logística", {"size": 18, "gap": 10}),
    ("Desarrollamos el sitio web con ingreso seguro y pedidos con seña", {"size": 18}),
], size=16, bullets=False)
for i, (n, l) in enumerate([("USD 75.810", "inversión inicial del proyecto"), ("17 %", "margen neto por contenedor"), ("≈ 13 meses", "para recuperar la inversión")]):
    y = 1.7 + i * 1.65
    d.rect(s, 7.5, y, 5.1, 1.45, fill="111111")
    d.text(s, 7.7, y + 0.1, 2.7, 1.25, n, size=28, bold=True, color="FFFFFF", font="Arial", anchor="m")
    d.text(s, 10.3, y + 0.1, 2.2, 1.25, l, size=14, color="D9D9D9", anchor="m")

# 8 Compromisos
s = d.slide(title="Cómo cuidamos al cliente", notes="Ética, confidencialidad, seguridad de la información y seguridad e higiene.")
cm = [("Ética y anticorrupción", "Cero pagos indebidos. Contratos claros y trazables con compradores y agentes."),
      ("Confidencialidad", "Acuerdo de confidencialidad en cada proyecto y acceso solo a quien lo necesita."),
      ("Seguridad de la información", "Contraseñas cifradas, accesos registrados y copias de respaldo."),
      ("Seguridad e higiene", "Normas de ingreso a planta y protocolo propio para visitas del equipo.")]
for i, (t, desc) in enumerate(cm):
    c, r = i % 2, i // 2
    x, y = 0.7 + c * 6.1, 1.75 + r * 2.5
    d.rect(s, x, y, 5.8, 2.25, fill=SOFT)
    d.num(s, x + 0.3, y + 0.3, 0.6, i + 1, "111111", size=20)
    d.text(s, x + 1.1, y + 0.25, 4.5, 0.5, t, size=20, bold=True, font="Arial")
    d.text(s, x + 1.1, y + 0.85, 4.5, 1.3, desc, size=15, color=MUT)

# 9 Contacto
s = d.slide(dark=True, notes="Cierre: contacto por WhatsApp 11 3348-6017.")
d.image(s, "01_Logo/logo_consultora_negativo.png", 0.7, 1.2, w=7.2, alt="Logo de Rumbo Global Consultores S.A.")
d.text(s, 0.7, 3.8, 11, 0.8, "Llevamos a las pymes argentinas al mundo", size=34, bold=True, font="Arial")
d.text(s, 0.7, 4.9, 8, 1.6, [("WhatsApp: 11 3348-6017", {"size": 24, "bold": True, "color": "FFFFFF"}),
                             ("Escribinos y armamos tu plan de exportación", {"size": 18, "color": "D0D0D0"})], size=20)
d.rect(s, 9.2, 4.9, 3.4, 1.0, fill=RED)
d.text(s, 9.2, 4.9, 3.4, 1.0, "Quiero exportar", size=22, bold=True, color="FFFFFF", align="c", anchor="m", font="Arial")

out = d.save("02_Presentaciones/01_Presentacion_Consultora_RumboGlobal.pptx")
print(out)
for w in WARN:
    print("WARN", w)
