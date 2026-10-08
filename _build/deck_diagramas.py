import sys
sys.path.insert(0, "_build")
from pp import Deck, WARN
from PIL import Image
from pptx.enum.shapes import MSO_SHAPE
from pptx.util import Inches, Pt

T = dict(dark="173A2B", light="FFFFFF", ink="1B2A22", on_dark="FFFFFF", head="Cambria", body="Calibri")
G, GOLD, SOFT, MUT = "1F4D3A", "C9A227", "F3F7F4", "566259"
d = Deck(T)
D = "03_Diagramas/"

# Portada
s = d.slide(dark=True, notes="Diagramas de flujo del proyecto ASHAB y de Rumbo Global: primero explicados simple y después en detalle.")
d.text(s, 0.7, 1.9, 11, 1.2, "Diagramas de flujo", size=54, bold=True, color="C9A227", font="Cambria", check=False)
d.text(s, 0.7, 3.3, 11, 1.6, [("Cómo funciona el proyecto ASHAB", {"size": 28, "bold": True, "font": "Cambria"}),
                              ("Explicado simple y explicado en detalle", {"size": 20, "color": "CFE0D6"})], size=28)
d.text(s, 0.7, 6.5, 8, 0.4, "Rumbo Global Consultores S.A. · 2026", size=14, color="9FB8AA")

# Cómo leer
s = d.slide(title="Cómo se leen los diagramas", notes="Convenciones de formas y colores.")
items = [("Inicio o fin", "oval", "Donde empieza o termina un proceso"), ("Paso o tarea", "round", "Algo que alguien hace"), ("Decisión", "diamond", "Una pregunta con respuesta SÍ o NO")]
for i, (n, kind, dsc) in enumerate(items):
    x = 0.9 + i * 4.1
    if kind == "oval":
        d.rect(s, x, 1.9, 3.3, 1.3, fill=G, shape="round", radius=0.65)
    elif kind == "round":
        d.rect(s, x, 1.9, 3.3, 1.3, fill="FFFFFF", line=G, lw=3)
    else:
        dm = s.shapes.add_shape(MSO_SHAPE.DIAMOND, Inches(x), Inches(1.75), Inches(3.3), Inches(1.6))
        dm.fill.solid()
        dm.fill.fore_color.rgb = __import__("pp").rgb("FCF4D6")
        dm.line.color.rgb = __import__("pp").rgb(GOLD)
        dm.line.width = Pt(3)
    d.text(s, x, 3.5, 3.3, 0.5, n, size=20, bold=True, color=G, font="Cambria", align="c")
    d.text(s, x, 4.05, 3.3, 0.8, dsc, size=15, color=MUT, align="c")
d.text(s, 0.9, 5.2, 11.5, 1.6, [("Las flechas muestran el orden. Las franjas horizontales (carriles) indican quién hace cada paso: el cliente, la consultora, los organismos, el transporte o el importador.", {"size": 17, "gap": 8}),
                                ("Los círculos numerados muestran el orden en los diagramas simples.", {"size": 17})], size=17)

from diagrams_meta import DIAGS
shown = set()
for tipo, f, titulo, cap in DIAGS:
    if tipo not in shown:
        shown.add(tipo)
        s = d.slide(dark=True, title="Explicados simple" if tipo == "simple" else "Explicados en detalle", notes="Sección.")
        d.text(s, 0.7, 3.0, 11, 1.6, "Tres diagramas con pasos cortos y lenguaje cotidiano." if tipo == "simple" else "Seis diagramas con carriles, decisiones y correcciones: quién hace qué y qué pasa si algo sale mal.", size=22, color="CFE0D6")
    s = d.slide(title=titulo, size=30, notes=cap, th=0.8)
    im = Image.open(D + f)
    ratio = im.width / im.height
    maxw, maxh = 12.3, 4.85 if tipo == "complejo" else 4.9
    w = min(maxw, maxh * ratio)
    h = w / ratio
    d.image(s, D + f, (13.333 - w) / 2, 1.35, w=w, alt=titulo)
    d.text(s, 0.7, 6.35, 11.9, 0.95, cap, size=14 if len(cap) > 190 else 16, color=MUT)

d.save("02_Presentaciones/03_Diagramas_de_Flujo.pptx")
for w in WARN:
    print("WARN", w)
print("ok", d.n)
