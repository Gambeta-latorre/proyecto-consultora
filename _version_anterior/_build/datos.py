"""Datos y modelo de costos compartidos por el Excel, las presentaciones y los documentos."""

SUP = [  # (clave, etiqueta, valor, nota)
    ("tc", "Tipo de cambio ARS por USD", 1450, "Supuesto: actualizar al día de uso"),
    ("kg", "Kg netos por contenedor 40'", 24000, "Bolsas de 1 kg en cajas de 10 kg, contenedor seco de 40 pies"),
    ("c_yerba", "Costo yerba empacada en planta (USD/kg)", 1.55, "Precio del cliente puesto en planta, a confirmar con la yerbatera"),
    ("c_pack", "Packaging de marca ASHAB bilingüe (USD/kg)", 0.10, "Extra sobre el packaging estándar"),
    ("flete_int", "Flete interno Misiones a Buenos Aires (USD/contenedor)", 2400, "Aprox. 1.100 km"),
    ("g_exp", "Gastos de exportación (USD/contenedor)", 1600, "Despachante, THC origen, documentación, precintos"),
    ("flete_mar", "Flete marítimo Buenos Aires a Beirut/Jebel Ali (USD/contenedor)", 4200, "Incluye recargo por riesgo de zona; cotizar con naviera"),
    ("seguro", "Prima de seguro de carga (% del valor asegurado)", 0.006, ""),
    ("aseg_k", "Valor asegurado (multiplicador sobre CIF)", 1.10, "Práctica habitual: CIF + 10%"),
    ("derechos", "Derechos de exportación (% sobre FOB)", 0.0, "Supuesto 0%: verificar alícuota vigente en ARCA"),
    ("p_cif", "Precio de venta CIF (USD/kg)", 2.55, "Equivale a unos 2,36 USD/kg FOB"),
    ("lc", "Costo de carta de crédito (% de la venta)", 0.008, "Apertura, confirmación y gastos"),
    ("banc", "Gastos bancarios y cobranza (USD/contenedor)", 300, ""),
    ("sena", "Seña obligatoria para confirmar el pedido (% de la venta)", 0.5, "Se cobra antes de producir; el saldo, antes de cargar el contenedor"),
    ("com", "Honorario de éxito de Rumbo Global (% de la venta)", 0.03, "Se cobra solo por embarque cobrado"),
    ("n1", "Contenedores año 1", 6, "Primer embarque en el mes 7 y luego uno por mes"),
    ("n2", "Contenedores año 2", 14, ""),
    ("n3", "Contenedores año 3", 24, ""),
    ("f_web", "Mantenimiento web y sistema (USD/año, años 2 y 3)", 600, ""),
    ("f_agente", "Agente comercial en destino (USD/año, años 2 y 3)", 12000, ""),
    ("f_feria", "Feria anual (USD/año, años 2 y 3)", 8000, ""),
    ("f_seg", "Consultoría de seguimiento (USD/año, años 2 y 3)", 6000, ""),
    ("imprev", "Imprevistos sobre inversión inicial (%)", 0.05, ""),
]
S = {k: v for k, _, v, _ in SUP}

INVERSION = [  # (rubro, USD, detalle)
    ("Estudio de mercado y plan de exportación (consultora)", 8000, "Diagnóstico, selección de países, precios y plan a 3 años"),
    ("Registro de marca ASHAB (Argentina, Siria, Líbano, Jordania, EAU)", 6500, "Clase 30; tasas y honorarios de agentes locales"),
    ("Diseño de packaging y etiquetas en español, inglés y árabe", 3500, "Incluye adaptación de gráfica y pruebas de impresión"),
    ("Habilitaciones, análisis de laboratorio y certificados", 2800, "RNE, RNPA, análisis, certificado de origen y sanitarios"),
    ("Certificación halal (recomendada)", 2500, "Facilita el ingreso a cadenas y distribuidores"),
    ("Registros y trámites sanitarios en destino", 4000, "Agentes locales en los cuatro países"),
    ("Sitio web trilingüe y sistema de clientes (desarrollo)", 5500, "Español, inglés y árabe; inicio de sesión con DNI"),
    ("Dominio, hosting y mantenimiento (primer año)", 600, ""),
    ("Viaje de prospección: 2 personas, Dubái y Beirut, 10 días", 9000, "Pasajes, hotel, traslados y reuniones"),
    ("Muestras y courier internacional", 2000, "Hasta 30 muestras de 1 kg"),
    ("Feria Gulfood Dubái (stand compartido)", 12000, "Participación junto a otras empresas argentinas"),
    ("Consultoría de implementación (6 meses)", 13800, "6 cuotas de 2.300 USD"),
    ("Seguro de crédito a la exportación y contingencias", 2000, "Cobertura de riesgo comercial y político"),
]


def calcular():
    s = S
    r = {}
    r["merc"] = s["kg"] * s["c_yerba"]
    r["pack"] = s["kg"] * s["c_pack"]
    r["fint"] = s["flete_int"]
    r["gexp"] = s["g_exp"]
    r["der"] = (r["merc"] + r["pack"] + r["fint"] + r["gexp"]) * s["derechos"]
    r["fob"] = r["merc"] + r["pack"] + r["fint"] + r["gexp"] + r["der"]
    r["fmar"] = s["flete_mar"]
    r["seg"] = (r["fob"] + r["fmar"]) * s["aseg_k"] * s["seguro"]
    r["cif"] = r["fob"] + r["fmar"] + r["seg"]
    r["venta"] = s["kg"] * s["p_cif"]
    r["lc"] = r["venta"] * s["lc"]
    r["banc"] = s["banc"]
    r["com"] = r["venta"] * s["com"]
    r["total"] = r["cif"] + r["lc"] + r["banc"] + r["com"]
    r["margen"] = r["venta"] - r["total"]
    r["margen_pct"] = r["margen"] / r["venta"]
    r["costo_kg"] = r["total"] / s["kg"]
    r["fob_kg"] = (r["venta"] - r["fmar"] - r["seg"]) / s["kg"]
    r["inv_sub"] = sum(x[1] for x in INVERSION)
    r["imprev"] = r["inv_sub"] * s["imprev"]
    r["inv"] = r["inv_sub"] + r["imprev"]
    fijos = s["f_web"] + s["f_agente"] + s["f_feria"] + s["f_seg"]
    r["fijos"] = fijos
    acum = 0
    r["anios"] = []
    for i, n in enumerate((s["n1"], s["n2"], s["n3"]), 1):
        ing = n * r["venta"]
        var = n * r["total"]
        contrib = ing - var
        fij = 0 if i == 1 else fijos
        inv = r["inv"] if i == 1 else 0
        neto = contrib - fij - inv
        acum += neto
        r["anios"].append(dict(n=n, ing=ing, var=var, contrib=contrib, fijos=fij, inv=inv, neto=neto, acum=acum))
    r["capital_trabajo_sin_sena"] = 2 * r["total"]
    r["capital_trabajo"] = 2 * (r["total"] - r["venta"] * s["sena"])
    return r


def fmt(x, d=0):
    s = f"{x:,.{d}f}"
    return s.replace(",", "X").replace(".", ",").replace("X", ".")


if __name__ == "__main__":
    r = calcular()
    for k, v in r.items():
        print(k, v)
