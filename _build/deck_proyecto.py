import sys
sys.path.insert(0, "_build")
from pp import Deck, WARN, rgb
from datos import calcular, fmt, S, INVERSION
from pptx.util import Inches, Pt
from pptx.chart.data import CategoryChartData
from pptx.enum.chart import XL_CHART_TYPE, XL_LABEL_POSITION, XL_LEGEND_POSITION
from pptx.enum.text import PP_ALIGN, MSO_ANCHOR

R = calcular()
T = dict(dark="173A2B", light="FFFFFF", ink="1B2A22", on_dark="FFFFFF", head="Cambria", body="Calibri")
G, GOLD, SOFT, MUT = "1F4D3A", "C9A227", "F3F7F4", "566259"
d = Deck(T)


def table(s, x, y, w, colw, rows, size=14, rowh=0.5, head_fill=G):
    gf = s.shapes.add_table(len(rows), len(colw), Inches(x), Inches(y), Inches(w), Inches(rowh * len(rows)))
    tb = gf.table
    tblPr = gf._element.graphic.graphicData.tbl.tblPr
    tblPr.set("firstRow", "0")
    tblPr.set("bandRow", "0")
    for i, cw in enumerate(colw):
        tb.columns[i].width = Inches(cw)
    for ri, row in enumerate(rows):
        tb.rows[ri].height = Inches(rowh)
        for ci, val in enumerate(row):
            c = tb.cell(ri, ci)
            c.margin_left = c.margin_right = Inches(0.1)
            c.margin_top = c.margin_bottom = Inches(0.05)
            c.vertical_anchor = MSO_ANCHOR.MIDDLE
            c.fill.solid()
            c.fill.fore_color.rgb = rgb(head_fill if ri == 0 else ("FFFFFF" if ri % 2 else SOFT))
            tf = c.text_frame
            tf.word_wrap = True
            p = tf.paragraphs[0]
            r = p.add_run()
            r.text = str(val)
            r.font.size = Pt(size)
            r.font.name = "Calibri"
            r.font.bold = ri == 0 or ci == 0
            r.font.color.rgb = rgb("FFFFFF" if ri == 0 else T["ink"])
    return gf


def style_chart(ch, legend=False, size=12):
    ch.font.size = Pt(size)
    ch.font.name = "Calibri"
    ch.has_legend = legend
    if legend:
        ch.legend.position = XL_LEGEND_POSITION.BOTTOM
        ch.legend.include_in_layout = False


# 1 Portada
s = d.slide(dark=True, notes="Presentación del proyecto ASHAB: exportación de yerba mate argentina a Medio Oriente. Cliente ficticio con fines académicos.")
for r_, c_ in [(5.2, "1F4D3A"), (3.9, "245A44"), (2.6, "2B6B51")]:
    d.rect(s, 10.3 - r_ / 2 + 0.6, 3.75 - r_ / 2, r_, r_, fill=c_, shape="oval")
d.rect(s, 10.9 - 0.45, 3.75 - 0.45, 0.9, 0.9, fill=GOLD, shape="oval")
d.text(s, 0.7, 1.6, 7.5, 1.4, "ASHAB", size=80, bold=True, color=GOLD, font="Cambria", check=False)
d.text(s, 0.7, 3.05, 7.5, 0.9, "أعشاب الواحة", size=44, bold=True, color="FFFFFF", font="Arial", rtl=True, align="l")
d.text(s, 0.7, 4.2, 7.5, 1.3, [("Yerba mate argentina para Medio Oriente", {"size": 26, "bold": True, "font": "Cambria"}),
                               ("Presentación del proyecto del cliente", {"size": 18, "color": "CFE0D6"})], size=26)
d.text(s, 0.7, 6.5, 8, 0.4, "Asesorado por Rumbo Global Consultores S.A. · 2026", size=14, color="9FB8AA")

# 2 La oportunidad
s = d.slide(title="La oportunidad: Medio Oriente toma mate", notes="Datos del INYM y de la Bolsa de Comercio de Rosario: 2024 fue récord de exportaciones de yerba mate.")
stats = [("+44 mil t", "exportadas por Argentina en 2024, un récord"), ("≈ 70 %", "de los envíos se concentra en Siria y Líbano"), ("≈ 50", "países de destino de la yerba mate argentina")]
for i, (n, l) in enumerate(stats):
    x = 0.7 + i * 4.05
    d.rect(s, x, 1.75, 3.8, 2.6, fill=G)
    d.text(s, x + 0.25, 1.95, 3.3, 1.2, n, size=48, bold=True, color=GOLD, font="Cambria", anchor="m")
    d.text(s, x + 0.25, 3.15, 3.3, 1.1, l, size=17, color="FFFFFF")
d.text(s, 0.7, 4.75, 11.9, 1.5, [
    ("En Siria y Líbano el mate es una costumbre familiar de décadas, heredada de la inmigración y mantenida por el comercio con Argentina.", {"size": 18, "gap": 10}),
    ("ASHAB entra con marca propia, packaging en árabe y un precio pensado para el distribuidor mayorista.", {"size": 18}),
], size=18)
d.text(s, 0.7, 6.75, 11.9, 0.4, "Fuentes: INYM y Bolsa de Comercio de Rosario (2024–2025).", size=11, color=MUT)

# 3 Mercado en cifras
s = d.slide(title="El mercado en cifras y su riesgo", notes="Concentración en Siria y Líbano: por eso el proyecto diversifica con Jordania y Emiratos. En marzo de 2026 algunas navieras cancelaron reservas a Siria.")
cd = CategoryChartData()
cd.categories = ["Siria y Líbano", "Resto de destinos"]
cd.add_series("Participación", (70, 30))
gf = s.shapes.add_chart(XL_CHART_TYPE.DOUGHNUT, Inches(0.7), Inches(1.6), Inches(5.2), Inches(4.8), cd)
ch = gf.chart
style_chart(ch, legend=True, size=14)
ch.has_title = True
ch.chart_title.text_frame.text = "Destino de la yerba mate argentina (% aprox.)"
ch.chart_title.text_frame.paragraphs[0].runs[0].font.size = Pt(14)
ch.chart_title.text_frame.paragraphs[0].runs[0].font.bold = True
pl = ch.plots[0]
pl.has_data_labels = True
pl.data_labels.number_format = '0"%"'
pl.data_labels.number_format_is_linked = False
pl.data_labels.font.size = Pt(16)
pl.data_labels.font.bold = True
pl.data_labels.font.color.rgb = rgb("FFFFFF")
for i, c in enumerate([G, GOLD]):
    pt = pl.series[0].points[i]
    pt.format.fill.solid()
    pt.format.fill.fore_color.rgb = rgb(c)
d.rect(s, 6.4, 1.75, 6.2, 2.15, fill=SOFT)
d.text(s, 6.65, 1.85, 5.7, 1.95, [("Primer semestre de 2026", {"size": 18, "bold": True, "color": G, "font": "Cambria", "gap": 4}),
                                  ("26.045 toneladas exportadas por USD 48,3 millones: unos USD 1,85 por kg FOB en promedio.", {"size": 16})], size=16)
d.rect(s, 6.4, 4.15, 6.2, 2.25, fill="FFF4D6", line=GOLD)
d.text(s, 6.65, 4.25, 5.7, 2.05, [("Riesgo a gestionar", {"size": 18, "bold": True, "color": "8A6A00", "font": "Cambria", "gap": 4}),
                                  ("En marzo de 2026 la guerra en la región llevó a algunas navieras a cancelar reservas hacia Siria. Por eso ASHAB diversifica: Líbano, Jordania y Emiratos, con seguro y carta de crédito.", {"size": 16})], size=16)
d.text(s, 0.7, 6.75, 11.9, 0.4, "Fuentes: INYM, BCR, Infocampo e iProfesional. El promedio por kg es un cálculo propio a partir de datos aduaneros.", size=11, color=MUT)

# 4 Países
s = d.slide(title="Países elegidos", notes="Siria, Líbano, Jordania y Emiratos Árabes Unidos: dos mercados históricos de consumo, una vía terrestre y un centro de reexportación.")
paises = [("Siria", "Mercado histórico", "Mayor consumo de yerba mate de Medio Oriente. Ingreso por Latakia y Tartus o por tierra desde Líbano."),
          ("Líbano", "Puerta logística", "Puerto de Beirut. Consumo fuerte en la montaña y las ciudades; sirve de base para llegar a Siria."),
          ("Jordania", "Acceso terrestre", "Puerto de Áqaba. Comunidades sirias y libanesas que ya toman mate."),
          ("Emiratos", "Centro de reexportación", "Emiratos Árabes Unidos. Puerto de Jebel Ali y feria Gulfood en Dubái para conocer distribuidores de toda la región.")]
for i, (p_, rol, desc) in enumerate(paises):
    x = 0.7 + i * 3.05
    d.rect(s, x, 1.75, 2.85, 4.6, fill=SOFT)
    d.num(s, x + 0.25, 1.95, 0.6, i + 1, GOLD, color="1B2A22", size=20)
    d.text(s, x + 0.2, 2.75, 2.5, 0.55, p_, size=24, bold=True, color=G, font="Cambria")
    d.text(s, x + 0.2, 3.3, 2.5, 0.45, rol, size=15, bold=True, color="8A6A00")
    d.text(s, x + 0.2, 3.85, 2.5, 2.4, desc, size=15, color=MUT)
d.text(s, 0.7, 6.6, 11.9, 0.5, "La entrada a cada país depende de las condiciones sanitarias, aduaneras y de seguridad vigentes al momento del embarque.", size=13, color=MUT)

# 5 Propuesta ASHAB
s = d.slide(title="La propuesta: ASHAB, hierbas del oasis", notes="ASHAB significa hierbas en árabe; al-Wahat significa el oasis. Cuatro productos para distribución mayorista.")
d.text(s, 0.7, 1.5, 11.9, 0.9, "ASHAB (أعشاب) significa «hierbas» en árabe. Al-Wahat (الواحة) significa «el oasis»: hierbas del oasis, un nombre que se entiende en toda la región.", size=17, color=MUT)
prods = [("ASHAB Tradicional", "Con palo, sabor intenso y clásico", "Bolsa 1 kg · caja 10 kg"), ("ASHAB Suave", "Poco palo, molienda fina", "Bolsa 500 g · caja 10 kg"),
         ("ASHAB con Menta", "Con menta natural, fresca", "Bolsa 500 g · caja 10 kg"), ("ASHAB Premium", "Hoja seleccionada y más estacionada", "Bolsa 1 kg · caja 12 kg")]
for i, (n, dsc, pk) in enumerate(prods):
    x = 0.7 + i * 3.05
    d.rect(s, x, 2.7, 2.85, 3.5, fill=G)
    d.rect(s, x + 0.25, 2.95, 0.7, 0.7, fill=GOLD, shape="oval")
    d.text(s, x + 0.2, 3.85, 2.5, 0.7, n, size=18, bold=True, color="FFFFFF", font="Cambria")
    d.text(s, x + 0.2, 4.6, 2.5, 0.8, dsc, size=15, color="DCE9E1")
    d.text(s, x + 0.2, 5.5, 2.5, 0.6, pk, size=13, bold=True, color=GOLD)
d.text(s, 0.7, 6.5, 11.9, 0.5, "Etiquetas en árabe, español e inglés. Certificación halal prevista en el plan.", size=14, color=MUT)

# 6 Cómo funciona simple
s = d.slide(title="Cómo funciona, explicado simple", notes="Seis pasos desde la yerbatera hasta el mate en la mesa.")
d.image(s, "03_Diagramas/S1_como_funciona_ashab_simple.png", 0.9, 1.5, w=11.5, alt="Seis pasos: elabora, papeles, camión, barco, distribuidor, familias")
d.text(s, 0.7, 6.6, 11.9, 0.5, "Rumbo Global se ocupa de los pasos 2 a 5 junto con la yerbatera.", size=15, color=MUT)

# 7 Web simple
s = d.slide(title="Cómo se usa la página web", notes="Seis pasos para un cliente nuevo de la web de ASHAB.")
d.image(s, "03_Diagramas/S2_como_se_usa_la_web_simple.png", 0.9, 1.5, w=11.5, alt="Seis pasos para usar el sitio web")
d.text(s, 0.7, 6.6, 11.9, 0.5, "El botón verde de WhatsApp está en todas las páginas: 11 3348-6017.", size=15, color=MUT)

# 8 Web features
s = d.slide(title="Sitio web pensado para empresas árabes", notes="El sitio funciona en español, inglés y árabe, con lectura de derecha a izquierda.")
ft = [("3 idiomas", "Español, inglés y árabe. El árabe se lee de derecha a izquierda."), ("Ingreso con Google", "Sin contraseñas nuevas ni documentos personales: el email lo verifica Google."),
      ("Empresas aprobadas", "Se revisa el registro comercial antes de mostrar precios y aceptar pedidos."), ("Seña del 50 %", "Proforma online; sin la seña acreditada no se produce ni se reserva flete."),
      ("WhatsApp flotante", "Un toque para escribir al 11 3348-6017 con mensaje listo."), ("Control total", "El administrador ve usuarios, ingresos y pedidos, y exporta todo a Excel.")]
for i, (t_, dsc) in enumerate(ft):
    c, r = i % 3, i // 3
    x, y = 0.7 + c * 4.05, 1.75 + r * 2.55
    d.rect(s, x, y, 3.8, 2.3, fill=SOFT)
    d.num(s, x + 0.25, y + 0.25, 0.55, i + 1, G, size=18)
    d.text(s, x + 0.25, y + 0.95, 3.3, 0.45, t_, size=20, bold=True, color=G, font="Cambria")
    d.text(s, x + 0.25, y + 1.4, 3.3, 0.85, dsc, size=14, color=MUT)

# 9 Plan 12 meses
s = d.slide(title="Plan de los primeros 12 meses", notes="Cinco fases superpuestas. El primer embarque sale entre los meses 6 y 9.")
fases = [("1. Preparación y plan", 1, 3), ("2. Marca y habilitaciones", 2, 5), ("3. Prospección y Gulfood", 4, 8), ("4. Primer embarque", 6, 9), ("5. Escala y seguimiento", 9, 12)]
gx, gy, lw, mw = 0.7, 1.7, 3.2, 0.725
for m in range(12):
    d.rect(s, gx + lw + m * mw, gy, mw - 0.04, 0.5, fill=G, shape="rect")
    d.text(s, gx + lw + m * mw, gy, mw - 0.04, 0.5, str(m + 1), size=14, bold=True, color="FFFFFF", align="c", anchor="m", check=False)
d.text(s, gx, gy, lw, 0.5, "Mes", size=14, bold=True, color=G, anchor="m")
for i, (n, a, b) in enumerate(fases):
    y = gy + 0.7 + i * 0.85
    d.rect(s, gx, y, 11.9, 0.7, fill=SOFT if i % 2 == 0 else "FFFFFF", shape="rect")
    d.text(s, gx + 0.1, y, lw - 0.1, 0.7, n, size=15, bold=True, anchor="m")
    d.rect(s, gx + lw + (a - 1) * mw, y + 0.12, (b - a + 1) * mw - 0.04, 0.46, fill=GOLD)
d.text(s, 0.7, 6.55, 11.9, 0.5, "Hitos: marca registrada (mes 5), muestras en destino (mes 6), primer contenedor embarcado (mes 7), primer cobro (mes 10).", size=14, color=MUT)

# 10 Inversión
a = R
grupos = [("Estudio, plan y consultoría", INVERSION[0][1] + INVERSION[11][1]), ("Marca y packaging", INVERSION[1][1] + INVERSION[2][1]),
          ("Habilitaciones y certificados", INVERSION[3][1] + INVERSION[4][1] + INVERSION[5][1]), ("Web y sistema", INVERSION[6][1] + INVERSION[7][1]),
          ("Prospección, muestras y feria", INVERSION[8][1] + INVERSION[9][1] + INVERSION[10][1]), ("Seguro e imprevistos", INVERSION[12][1] + R["imprev"])]
s = d.slide(title="Inversión inicial", notes="Se paga una sola vez durante el primer año. Además se necesita capital de trabajo para financiar los primeros contenedores.")
cd = CategoryChartData()
cd.categories = [g[0] for g in reversed(grupos)]
cd.add_series("USD", [round(g[1]) for g in reversed(grupos)])
gf = s.shapes.add_chart(XL_CHART_TYPE.BAR_CLUSTERED, Inches(0.7), Inches(1.6), Inches(7.4), Inches(4.9), cd)
ch = gf.chart
style_chart(ch, size=13)
pl = ch.plots[0]
pl.gap_width = 60
pl.has_data_labels = True
pl.data_labels.number_format = '#,##0'
pl.data_labels.number_format_is_linked = False
pl.data_labels.position = XL_LABEL_POSITION.OUTSIDE_END
pl.data_labels.font.size = Pt(13)
pl.series[0].format.fill.solid()
pl.series[0].format.fill.fore_color.rgb = rgb(G)
ch.value_axis.visible = False
ch.value_axis.has_major_gridlines = False
ch.category_axis.format.line.fill.background()
ch.has_title = True
ch.chart_title.text_frame.text = "Inversión por rubro (USD)"
ch.chart_title.text_frame.paragraphs[0].runs[0].font.size = Pt(14)
for i, (n, l) in enumerate([(f"USD {fmt(R['inv'])}", "inversión inicial total"), (f"USD {fmt(R['capital_trabajo'])}", "capital de trabajo con seña del 50 % (2 contenedores)"), (f"USD {fmt(R['inv'] + R['capital_trabajo'])}", "necesidad máxima de financiamiento")]):
    y = 1.7 + i * 1.65
    d.rect(s, 8.5, y, 4.1, 1.45, fill=G if i < 2 else GOLD)
    d.text(s, 8.65, y + 0.1, 3.8, 0.7, n, size=26, bold=True, color="FFFFFF" if i < 2 else "1B2A22", font="Cambria", anchor="m")
    d.text(s, 8.65, y + 0.8, 3.8, 0.6, l, size=14, color="DCE9E1" if i < 2 else "1B2A22")
d.text(s, 0.7, 6.7, 11.9, 0.4, "Valores estimados en USD; tipo de cambio de referencia ARS 1.450 por USD (supuesto). Detalle completo en el Excel de costos.", size=11, color=MUT)

# 11 Rentabilidad
s = d.slide(title="Rentabilidad: cuánto deja cada contenedor", notes="Cada contenedor de 40 pies lleva 24 toneladas. El margen neto es 17% de la venta.")
cd = CategoryChartData()
cd.categories = ["Año 1", "Año 2", "Año 3"]
cd.add_series("Flujo neto del año", [round(x["neto"]) for x in R["anios"]])
cd.add_series("Flujo acumulado", [round(x["acum"]) for x in R["anios"]])
gf = s.shapes.add_chart(XL_CHART_TYPE.COLUMN_CLUSTERED, Inches(0.7), Inches(1.6), Inches(7.0), Inches(4.9), cd)
ch = gf.chart
style_chart(ch, legend=True, size=13)
pl = ch.plots[0]
pl.gap_width = 70
pl.has_data_labels = True
pl.data_labels.number_format = '#,##0'
pl.data_labels.number_format_is_linked = False
pl.data_labels.position = XL_LABEL_POSITION.OUTSIDE_END
pl.data_labels.font.size = Pt(12)
for i, c in enumerate([G, GOLD]):
    pl.series[i].format.fill.solid()
    pl.series[i].format.fill.fore_color.rgb = rgb(c)
ch.value_axis.has_major_gridlines = True
ch.value_axis.major_gridlines.format.line.color.rgb = rgb("E3E8E5")
ch.value_axis.tick_labels.number_format = '#,##0'
ch.value_axis.tick_labels.number_format_is_linked = False
ch.has_title = True
ch.chart_title.text_frame.text = "Flujo de fondos (USD)"
ch.chart_title.text_frame.paragraphs[0].runs[0].font.size = Pt(14)
kp = [(f"USD {fmt(R['margen'])}", "margen neto por contenedor"), (f"{R['margen_pct']*100:.0f} %".replace(".", ","), "margen sobre la venta"), ("≈ 13 meses", "para recuperar la inversión")]
for i, (n, l) in enumerate(kp):
    y = 1.7 + i * 1.65
    d.rect(s, 8.1, y, 4.5, 1.45, fill=SOFT)
    d.text(s, 8.25, y + 0.1, 4.2, 0.75, n, size=28, bold=True, color=G, font="Cambria", anchor="m")
    d.text(s, 8.25, y + 0.85, 4.2, 0.5, l, size=15, color=MUT)
d.text(s, 0.7, 6.7, 11.9, 0.4, f"Base: {S['n1']}, {S['n2']} y {S['n3']} contenedores de 40' en los años 1, 2 y 3. Sin impuesto a las ganancias ni costo financiero.", size=11, color=MUT)

# 12 Un contenedor
s = d.slide(title="Un contenedor de 24 toneladas, paso a paso", notes="Del precio de planta al cobro. El precio de venta supera el promedio aduanero por la marca, el packaging bilingüe y la certificación.")
rows = [["Concepto", "USD", "USD por kg"],
        ["Yerba y packaging de marca", fmt(R["merc"] + R["pack"]), fmt((R["merc"] + R["pack"]) / S["kg"], 2)],
        ["Flete interno y gastos de exportación", fmt(R["fint"] + R["gexp"]), fmt((R["fint"] + R["gexp"]) / S["kg"], 2)],
        ["Flete marítimo y seguro", fmt(R["fmar"] + R["seg"]), fmt((R["fmar"] + R["seg"]) / S["kg"], 2)],
        ["Carta de crédito, bancos y honorario de éxito", fmt(R["lc"] + R["banc"] + R["com"]), fmt((R["lc"] + R["banc"] + R["com"]) / S["kg"], 2)],
        ["Costo total", fmt(R["total"]), fmt(R["costo_kg"], 2)],
        ["Venta CIF al importador", fmt(R["venta"]), fmt(S["p_cif"], 2)],
        ["Margen neto", fmt(R["margen"]), fmt(R["margen"] / S["kg"], 2)]]
table(s, 0.7, 1.65, 7.6, [4.6, 1.5, 1.5], rows, size=15, rowh=0.55)
d.rect(s, 8.7, 1.65, 3.9, 4.4, fill=G)
d.text(s, 8.95, 1.8, 3.4, 4.2, [("Precio mínimo", {"size": 18, "bold": True, "color": GOLD, "font": "Cambria", "gap": 4}),
                                ("Con un precio CIF de USD 2,10 por kg el contenedor ya no deja margen: el precio de venta puede bajar cerca de 18 % antes de perder dinero.", {"size": 16, "color": "FFFFFF", "gap": 12}),
                                ("Ojo con el promedio", {"size": 18, "bold": True, "color": GOLD, "font": "Cambria", "gap": 4}),
                                ("El promedio aduanero ronda USD 1,85 FOB. ASHAB apunta a USD 2,36: hay que justificarlo con marca, packaging y certificación.", {"size": 16, "color": "FFFFFF"})], size=16)
d.text(s, 0.7, 6.3, 7.6, 0.5, "Valores en USD por contenedor de 24.000 kg, bolsas de 1 kg.", size=12, color=MUT)

# 13 Riesgos
s = d.slide(title="Riesgos y cómo los cubrimos", notes="Matriz de riesgos principal del proyecto.")
rows = [["Riesgo", "Qué puede pasar", "Cómo lo cubrimos"],
        ["Conflicto en la región", "Navieras cancelan reservas o suben recargos", "Varios puertos y países, seguro y cláusulas de fuerza mayor"],
        ["Cobro y pedidos falsos", "El importador no paga o cancela", "Seña del 50 % antes de producir, saldo antes de cargar y empresa aprobada"],
        ["Sanciones y normas", "Restricciones bancarias o aduaneras", "Revisión legal previa y bancos con experiencia en la zona"],
        ["Sanitario", "Rechazo en la aduana de destino", "Análisis de laboratorio, certificados y etiquetado correcto"],
        ["Tipo de cambio", "Cambian costos en pesos", "Contratos en USD y costos principales dolarizados"],
        ["Precio", "La competencia baja precios", "Marca, packaging y relación con el distribuidor"]]
table(s, 0.7, 1.65, 11.9, [2.6, 4.1, 5.2], rows, size=14, rowh=0.68)

# 14 Cierre
s = d.slide(dark=True, title="Próximos pasos", title_color="FFFFFF", notes="Cierre: decisiones que necesita el cliente para arrancar.")
pasos = [("Aprobar el presupuesto", "e iniciar el diagnóstico"), ("Confirmar precios de planta", "y capacidad mensual de la yerbatera"), ("Registrar la marca ASHAB", "y encargar el packaging en tres idiomas"), ("Preparar muestras", "para el viaje a Beirut y Dubái")]
for i, (a_, b_) in enumerate(pasos):
    y = 1.7 + i * 1.1
    d.num(s, 0.7, y, 0.7, i + 1, GOLD, color="1B2A22", size=22)
    d.text(s, 1.65, y - 0.05, 7.2, 0.9, [(a_, {"size": 22, "bold": True, "font": "Cambria", "gap": 0}), (b_, {"size": 16, "color": "CFE0D6"})], size=22)
d.rect(s, 9.2, 1.7, 3.4, 4.1, fill="1F4D3A")
d.text(s, 9.4, 1.9, 3.0, 3.7, [("Hablemos", {"size": 24, "bold": True, "color": GOLD, "font": "Cambria", "gap": 8}), ("WhatsApp", {"size": 15, "color": "CFE0D6", "gap": 0}),
                               ("11 3348-6017", {"size": 24, "bold": True, "color": "FFFFFF", "gap": 14}), ("Rumbo Global Consultores S.A.", {"size": 15, "color": "CFE0D6"})], size=16)

d.save("02_Presentaciones/02_Presentacion_Proyecto_ASHAB.pptx")
for w in WARN:
    print("WARN", w)
print("ok", d.n)
