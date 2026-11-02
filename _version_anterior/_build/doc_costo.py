import sys
sys.path.insert(0, "_build")
from dx import Doc
from datos import calcular, fmt, S, INVERSION

R = calcular()
U = lambda x, d=0: "USD " + fmt(x, d)
D = Doc("ash", "Costo del proyecto ASHAB")
D.cover("Costo del proyecto", "Proyecto ASHAB · exportación de yerba mate a Medio Oriente",
        ["Versión 1.0 · 2026", "Preparado por Rumbo Global Consultores S.A.", "Planilla de cálculo asociada: Costos_del_Proyecto_ASHAB.xlsx"])

D.h1("1. Resumen")
D.p(f"Exportar yerba mate con marca propia a Siria, Líbano, Jordania y Emiratos requiere una inversión inicial de {U(R['inv'])} y un capital de trabajo de {U(R['capital_trabajo'])} para financiar los dos primeros contenedores, gracias a la seña obligatoria del 50 % (sin seña haría falta {U(R['capital_trabajo_sin_sena'])}). La necesidad máxima de financiamiento es de {U(R['inv'] + R['capital_trabajo'])}, que se recupera a medida que se cobran los embarques.")
D.table([["Indicador", "Valor"],
         ["Inversión inicial (una sola vez)", U(R["inv"])],
         ["Capital de trabajo con seña del 50 % (2 contenedores)", U(R["capital_trabajo"])],
         ["Necesidad máxima de financiamiento", U(R["inv"] + R["capital_trabajo"])],
         ["Costo total por contenedor de 40' (24 t)", U(R["total"])],
         ["Venta por contenedor (CIF)", U(R["venta"])],
         ["Margen neto por contenedor", f"{U(R['margen'])} ({R['margen_pct']*100:.1f} %)".replace(".", ",")],
         ["Recuperación de la inversión", "alrededor de 13 meses desde el inicio"],
         ["Flujo acumulado a 3 años (caso base)", U(R["anios"][2]["acum"])]],
        widths=[10, 6.5], size=10.5, bold_first_col=True)
D.callout("Cómo leer los números", "Son estimaciones para evaluar el proyecto. Los precios de la yerbatera, el flete marítimo y los aranceles deben confirmarse con cotizaciones reales antes de comprometer fondos. Todo el modelo se recalcula cambiando las celdas azules de la hoja «Supuestos» del Excel.", "warn")

D.h1("2. Supuestos principales")
rows = [["Supuesto", "Valor"]]
sel = ["tc", "sena", "kg", "c_yerba", "c_pack", "flete_int", "g_exp", "flete_mar", "seguro", "derechos", "p_cif", "lc", "com", "n1", "n2", "n3"]
labels = {k: (l, v) for k, l, v, _ in __import__("datos").SUP}
for k in sel:
    l, v = labels[k]
    if k in ("seguro", "derechos", "lc", "com", "sena"):
        val = f"{v*100:.1f} %".replace(".", ",")
    elif k in ("c_yerba", "c_pack", "p_cif"):
        val = U(v, 2)
    elif k in ("tc", "kg", "n1", "n2", "n3"):
        val = fmt(v)
    else:
        val = U(v)
    rows.append([l, val])
D.table(rows, widths=[11.5, 5], size=10, bold_first_col=False, align_right_from=1)
D.p("El tipo de cambio es solo una referencia para pasar a pesos: el proyecto se calcula en dólares porque se vende y se cobra en dólares.", italic=True, size=10)

D.h1("3. Inversión inicial")
rows = [["Rubro", "USD"]] + [[r[0], fmt(r[1])] for r in INVERSION]
rows += [["Subtotal", fmt(R["inv_sub"])], ["Imprevistos (5 %)", fmt(R["imprev"])], ["Inversión inicial total", fmt(R["inv"])]]
D.table(rows, widths=[13, 3.5], size=10, align_right_from=1)
D.p(f"En pesos, a ARS {fmt(S['tc'])} por dólar, equivale a ARS {fmt(R['inv'] * S['tc'])}.")

D.h1("4. Costo y margen de un contenedor")
D.table([["Concepto", "USD", "USD por kg"],
         ["Yerba empacada en planta", fmt(R["merc"]), fmt(R["merc"] / S["kg"], 2)],
         ["Packaging de marca bilingüe", fmt(R["pack"]), fmt(R["pack"] / S["kg"], 2)],
         ["Flete interno a Buenos Aires", fmt(R["fint"]), fmt(R["fint"] / S["kg"], 2)],
         ["Gastos de exportación", fmt(R["gexp"]), fmt(R["gexp"] / S["kg"], 2)],
         ["Costo FOB", fmt(R["fob"]), fmt(R["fob"] / S["kg"], 2)],
         ["Flete marítimo", fmt(R["fmar"]), fmt(R["fmar"] / S["kg"], 2)],
         ["Seguro de carga", fmt(R["seg"]), fmt(R["seg"] / S["kg"], 2)],
         ["Costo CIF", fmt(R["cif"]), fmt(R["cif"] / S["kg"], 2)],
         ["Carta de crédito", fmt(R["lc"]), fmt(R["lc"] / S["kg"], 2)],
         ["Gastos bancarios y cobranza", fmt(R["banc"]), fmt(R["banc"] / S["kg"], 2)],
         ["Honorario de éxito de Rumbo Global (3 %)", fmt(R["com"]), fmt(R["com"] / S["kg"], 2)],
         ["Costo total", fmt(R["total"]), fmt(R["costo_kg"], 2)],
         ["Venta CIF al importador", fmt(R["venta"]), fmt(S["p_cif"], 2)],
         ["Margen neto", fmt(R["margen"]), fmt(R["margen"] / S["kg"], 2)]],
        widths=[10, 3.2, 3.3], size=10, align_right_from=1)
D.p(f"El precio de venta CIF de USD {fmt(S['p_cif'], 2)} por kilo equivale a USD {fmt(R['fob_kg'], 2)} FOB. El promedio de las exportaciones argentinas en el primer semestre de 2026 fue de unos USD 1,85 por kilo FOB (cálculo propio con datos aduaneros publicados). La diferencia debe justificarse con marca, packaging en tres idiomas y certificación halal, porque el mercado compara con yerba a granel.")

D.h1("5. Proyección a tres años")
rows = [["Concepto", "Año 1", "Año 2", "Año 3"],
        ["Contenedores de 40'"] + [fmt(a["n"]) for a in R["anios"]],
        ["Ingresos por ventas"] + [fmt(a["ing"]) for a in R["anios"]],
        ["Costos variables"] + [fmt(a["var"]) for a in R["anios"]],
        ["Margen de contribución"] + [fmt(a["contrib"]) for a in R["anios"]],
        ["Costos fijos"] + [fmt(a["fijos"]) for a in R["anios"]],
        ["Inversión inicial"] + [fmt(a["inv"]) for a in R["anios"]],
        ["Flujo neto del año"] + [fmt(a["neto"]) for a in R["anios"]],
        ["Flujo acumulado"] + [fmt(a["acum"]) for a in R["anios"]]]
D.table(rows, widths=[7, 3.1, 3.2, 3.2], size=10, bold_first_col=True, align_right_from=1)
D.p(f"Los costos fijos de los años 2 y 3 ({U(R['fijos'])} por año) incluyen mantenimiento web, agente comercial en destino, una feria por año y consultoría de seguimiento. El año 1 cierra con un flujo levemente negativo porque se paga toda la inversión inicial y los embarques empiezan recién en el mes 7. La inversión se recupera durante el segundo año, alrededor del mes 13.")

D.h1("6. Escenarios")
inv, fijos = R["inv"], R["fijos"]
cif_banc = R["cif"] + S["banc"]
esc = []
for nom, dp, dv in (("Pesimista", -0.10, -0.30), ("Base", 0.0, 0.0), ("Optimista", 0.05, 0.20)):
    mc = S["kg"] * S["p_cif"] * (1 + dp) * (1 - S["lc"] - S["com"]) - cif_banc
    tot = mc * (S["n1"] + S["n2"] + S["n3"]) * (1 + dv) - inv - 2 * fijos
    esc.append([nom, f"{dp*100:+.0f} %".replace(".", ","), f"{dv*100:+.0f} %", fmt(mc), fmt(tot)])
D.table([["Escenario", "Precio", "Volumen", "Margen por contenedor (USD)", "Flujo acumulado a 3 años (USD)"]] + esc,
        widths=[3, 2.3, 2.3, 4.2, 4.7], size=10, bold_first_col=True, align_right_from=1)
pmin = cif_banc / (S["kg"] * (1 - S["lc"] - S["com"]))
D.callout("Precio mínimo", f"Con un precio CIF de USD {fmt(pmin, 2)} por kilo el contenedor deja margen cero. Eso es {abs(pmin / S['p_cif'] - 1) * 100:.0f} % menos que el precio previsto: es el colchón del proyecto frente a la competencia.", "info")

D.h1("7. Financiamiento")
D.bullets([("Inversión inicial: ", f"{U(R['inv'])}. Puede financiarse con capital propio de la yerbatera y, en parte, con programas de promoción de exportaciones o financiamiento de bancos."),
           ("Capital de trabajo: ", f"{U(R['capital_trabajo'])} con la seña del 50 % que exige el sitio (sin seña serían {U(R['capital_trabajo_sin_sena'])}). Se reduce más si se descuenta la carta de crédito."),
           ("Seguro de crédito: ", "recomendado cuando el pago no está respaldado por una carta de crédito confirmada.")])

D.h1("8. Qué no incluyen los números")
D.bullets(["Impuesto a las ganancias y otros impuestos locales. Las exportaciones están exentas de IVA y el crédito fiscal se recupera, pero el trámite tarda y no se modeló.",
           "Derechos de exportación: se supuso 0 %. Verificar la alícuota vigente en ARCA antes de cerrar precios.",
           "Costo financiero de los plazos de pago y variaciones de tipo de cambio.",
           "Reintegros por exportación y programas de promoción, que podrían mejorar el resultado.",
           "Costos de adaptación de la planta (por ejemplo, si el cliente debe mejorar instalaciones para cumplir requisitos sanitarios de destino)."])

D.h1("9. Cómo usar la planilla de Excel")
D.numbered(["Abrí Costos_del_Proyecto_ASHAB.xlsx.", "En la hoja «Supuestos» cambiá solo las celdas azules.", "Las hojas «Costo por contenedor», «Inversión inicial», «Proyección 3 años» y «Escenarios» se recalculan solas.",
            "Para estudiar otro precio, cambiá «Precio de venta CIF» y mirá el margen por contenedor."])
D.save("06_Documentos/Costo_del_Proyecto_ASHAB.docx")
print("costo ok")
