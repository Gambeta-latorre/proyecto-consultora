"""Prueba de punta a punta del sitio ASHAB con SQLite y un Google simulado.
Uso (desde la carpeta del proyecto):  python _build/tests/test_ashab.py
"""
import base64, json, os, re, subprocess, sys, time, urllib.parse, urllib.request, http.cookiejar

sys.stdout.reconfigure(encoding="utf-8")
ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", ".."))
SITE = os.path.join(ROOT, "05_Web_Proyecto")
DB = os.path.join(ROOT, "_build", "tests", "ashab_test.sqlite")
PHP = os.environ.get("PHP_BIN", "C:/riquelme/php/php")
APP, MOCK = 8200, 8201
BASE = f"http://localhost:{APP}"
if os.path.exists(DB):
    os.remove(DB)

env = dict(os.environ, DB_DSN="sqlite:" + DB, GOOGLE_CLIENT_ID="test-client", GOOGLE_CLIENT_SECRET="secret",
           GOOGLE_AUTH_URL=f"http://localhost:{MOCK}/auth", GOOGLE_TOKEN_URL=f"http://localhost:{MOCK}/token",
           SETUP_TOKEN="tok123", ADMIN_EMAILS="boss@example.com", APP_URL=BASE, BANK_INFO="Beneficiario: X\\nIBAN: Y")
procs = [
    subprocess.Popen([PHP, "-S", f"localhost:{APP}", "-t", "public", "router.php"], cwd=SITE, env=env, stdout=subprocess.DEVNULL, stderr=open(os.path.join(ROOT, "_build", "tests", "app.log"), "w")),
    subprocess.Popen([PHP, "-S", f"localhost:{MOCK}", os.path.join(ROOT, "_build", "tests", "mock_google.php")], env=env, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL),
]
time.sleep(2)

ok_n = fail_n = 0


def check(name, cond, extra=""):
    global ok_n, fail_n
    if cond:
        ok_n += 1
        print("  ok  ", name)
    else:
        fail_n += 1
        print("  FAIL", name, extra)


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


class Client:
    def __init__(self, lang_header="en"):
        self.jar = http.cookiejar.CookieJar()
        self.op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar), NoRedirect)
        self.lang = lang_header

    def req(self, path, data=None):
        url = path if path.startswith("http") else BASE + path
        body = urllib.parse.urlencode(data).encode() if data is not None else None
        r = urllib.request.Request(url, data=body, headers={"Accept-Language": self.lang, "User-Agent": "test-agent"})
        try:
            resp = self.op.open(r, timeout=20)
        except urllib.error.HTTPError as e:
            resp = e
        txt = resp.read().decode("utf-8", "replace")
        return resp.status if hasattr(resp, "status") else resp.code, dict(resp.headers), txt

    def csrf(self, path):
        _, _, t = self.req(path)
        m = re.search(r'name="csrf" value="([^"]+)"', t)
        return m.group(1) if m else ""

    def post(self, path, data, csrf_from=None):
        data = dict(data, csrf=self.csrf(csrf_from or path))
        return self.req(path, data)


def b64(d):
    return base64.urlsafe_b64encode(json.dumps(d).encode()).decode().rstrip("=")


def google_login(c, email, name="Buyer", sub="sub-1", **over):
    c.csrf("/login")
    s, h, _ = c.post("/google", {}, "/login")
    loc = h.get("Location", "")
    q = urllib.parse.parse_qs(urllib.parse.urlparse(loc).query)
    state, nonce = q.get("state", [""])[0], q.get("nonce", [""])[0]
    claims = dict(aud="test-client", iss="https://accounts.google.com", exp=int(time.time()) + 3600, nonce=nonce, sub=sub,
                  email=email, email_verified=True, name=name)
    claims.update(over)
    st = over.pop("_state", state)
    s2, h2, _ = c.req(f"/google_callback?code={b64(claims)}&state={state}")
    return s, loc, s2, h2.get("Location", "")


try:
    print("== instalación")
    c0 = Client()
    s, _, t = c0.req("/install?token=bad")
    check("install con token malo da 404", s == 404)
    s, _, t = c0.req("/install?token=tok123")
    check("install con token bueno crea tablas y productos", s == 200 and "Listo" in t and "cargados: 4" in t, t)

    print("== idioma / páginas públicas")
    s, _, t = Client("ar").req("/")
    check("árabe por navegador => dir=rtl", 'dir="rtl"' in t and 'lang="ar"' in t)
    s, _, t = Client("fr").req("/productos")
    check("idioma desconocido => inglés", 'lang="en"' in t and "Log in to see the price" in t)
    check("botón WhatsApp con el número 5491133486017", "wa.me/5491133486017" in t)
    s, _, t = Client("ar").req("/contacto")
    check("contacto muestra número internacional y QR", "+54 9 11 3348-6017" in t and "qr_whatsapp.png" in t)
    s, _, _ = Client().req("/inexistente")
    check("ruta desconocida da 404", s == 404)
    s, _, _ = Client().req("/src/core.php")
    check("no se puede leer código fuente", s == 404)
    s, h, _ = Client().req("/")
    check("cabeceras de seguridad", "Content-Security-Policy" in h and h.get("X-Frame-Options") == "DENY")

    print("== ingreso con Google")
    buyer = Client("ar")
    s, loc, s2, loc2 = google_login(buyer, "buyer@example.com", "Omar Haddad")
    check("POST /google redirige al proveedor con state y nonce", s == 302 and "state=" in loc and "nonce=" in loc)
    check("primer ingreso va a /perfil", s2 == 302 and loc2.endswith("/perfil"), loc2)
    # intentos inválidos
    for nombre, over in [("audiencia incorrecta", dict(aud="otro")), ("email sin verificar", dict(email_verified=False)), ("token vencido", dict(exp=1)), ("emisor falso", dict(iss="https://evil.com")), ("nonce incorrecto", dict(nonce="zzz"))]:
        cx = Client()
        _, _, s2x, l2x = google_login(cx, "x@example.com", sub="sub-x", **over)
        _, _, tpanel = cx.req("/panel")
        check("rechaza " + nombre, l2x.endswith("/login") and "Log in" in tpanel or l2x.endswith("/login"), l2x)
    cx = Client()
    cx.csrf("/login")
    cx.post("/google", {}, "/login")
    s, h, _ = cx.req("/google_callback?code=abc&state=falso")
    check("rechaza state falso", h.get("Location", "").endswith("/login"))
    s, h, _ = Client().req("/google", {"csrf": "x"})
    check("/google sin CSRF no inicia el flujo", h.get("Location", "").endswith("/login"))
    s, h, _ = Client().req("/panel")
    check("panel sin sesión redirige a /login", h.get("Location", "").endswith("/login"))

    print("== perfil de empresa y aprobación")
    s, h, t = buyer.post("/perfil", {"empresa": "", "pais": "LB", "ciudad": "", "registro_comercial": "", "whatsapp": "123"}, "/perfil")
    check("perfil inválido muestra errores", s == 200 and t.count("alert error") >= 3)
    s, h, t = buyer.post("/perfil", {"empresa": "Beirut Trading", "pais": "LB", "ciudad": "Beirut", "cargo": "Compras", "registro_comercial": "RC-12345", "whatsapp": "+961 3 123 456", "sitio_web": "https://bt.example"}, "/perfil")
    check("perfil válido guarda y va al panel", s == 302 and h["Location"].endswith("/panel"))
    s, _, t = buyer.req("/panel")
    check("cuenta en revisión (árabe)", "قيد المراجعة" in t)
    s, _, t = buyer.req("/productos")
    check("pendiente: no ve precios", "USD 2.10" not in t and "يظهر السعر بعد اعتماد شركتك" in t)
    s, _, t = buyer.post("/productos", {"producto_id": 1, "kg": 2000, "destino": "LB", "incoterm": "FOB"}, "/contacto")
    check("pendiente: no puede pedir", "need_approval" in t or "لم يتم اعتماد شركتك بعد" in t)

    admin = Client("es")
    s, loc, s2, loc2 = google_login(admin, "boss@example.com", "Jefe", sub="sub-admin")
    check("admin entra directo a /admin", loc2.endswith("/admin"), loc2)
    s, _, t = admin.req("/admin?tab=usuarios")
    check("admin ve al comprador con su registro comercial", s == 200 and "buyer@example.com" in t and "RC-12345" in t)
    s, _, t = buyer.req("/admin")
    check("comprador no entra a /admin (403)", s == 403)
    s, _, t = buyer.req("/admin_export?tipo=usuarios")
    check("comprador no exporta CSV (403)", s == 403)
    uid = re.search(r'name="id" value="(\d+)"', admin.req("/admin?tab=usuarios")[2])
    # buscar el id del comprador
    rows = re.findall(r'buyer@example\.com.*?name="id" value="(\d+)"', admin.req("/admin?tab=usuarios")[2], re.S)
    buyer_id = rows[0]
    s, _, t = admin.post("/admin?tab=usuarios", {"accion": "user_cuenta", "id": buyer_id, "cuenta": "aprobada", "nota": "RC verificado"}, "/admin?tab=usuarios")
    check("admin aprueba la cuenta", "Cuenta actualizada" in t)

    print("== pedidos y seña del 50 %")
    s, _, t = buyer.req("/productos")
    check("aprobado: ve precio FOB", "USD 2.10" in t)
    s, _, t = buyer.post("/productos", {"producto_id": 1, "kg": 500, "destino": "LB", "incoterm": "FOB"}, "/productos")
    check("rechaza cantidad menor al mínimo", "qty_bad" in t or "يجب أن تكون الكمية" in t)
    s, h, t = buyer.post("/productos", {"producto_id": 1, "kg": 2000, "destino": "LB", "incoterm": "FOB"}, "/productos")
    check("crea pedido válido", s == 302 and h["Location"].endswith("/panel"))
    _, _, t = buyer.req("/panel")
    code = re.search(r"ASH-[A-Z0-9]{6}", t).group(0)
    check("pedido listado con código", bool(code))
    s, _, t = buyer.req("/pedido?c=" + code)
    check("pedido nuevo: esperando proforma", "نجهّز فاتورتك المبدئية" in t)

    s, _, t = admin.req("/admin?tab=pedidos&c=" + code)
    check("admin ve el pedido", code in t and "Emitir proforma" in t)
    s, _, t = admin.post("/admin?tab=pedidos", {"accion": "proforma", "c": code, "precio": "2.2", "dias": "5"}, "/admin?tab=pedidos&c=" + code)
    check("admin emite proforma", "Proforma emitida" in t)
    s, _, t = buyer.req("/pedido?c=" + code)
    check("cliente ve total USD 4,400.00 y seña USD 2,200.00", "USD 4,400.00" in t and "USD 2,200.00" in t)
    check("cliente ve datos bancarios", "Beneficiario: X" in t and "IBAN: Y" in t)
    s, _, t = buyer.post("/pedido?c=" + code, {"c": code, "accion": "informar_pago", "pago_banco": "Bank Audi", "pago_ref": "TRX-1", "pago_fecha": time.strftime("%Y-%m-%d"), "pago_monto": "2200"}, "/pedido?c=" + code)
    check("no deja informar pago sin aceptar términos", "يرجى التحقق من بيانات الدفع" in t)
    s, _, t = buyer.post("/pedido?c=" + code, {"c": code, "accion": "informar_pago", "pago_banco": "Bank Audi", "pago_ref": "TRX-1", "pago_fecha": time.strftime("%Y-%m-%d"), "pago_monto": "9999", "acepto": "1"}, "/pedido?c=" + code)
    check("rechaza monto fuera de rango", "يرجى التحقق من بيانات الدفع" in t)
    s, h, t = buyer.post("/pedido?c=" + code, {"c": code, "accion": "informar_pago", "pago_banco": "Bank Audi", "pago_ref": "TRX-1", "pago_fecha": time.strftime("%Y-%m-%d"), "pago_monto": "2200", "acepto": "1"}, "/pedido?c=" + code)
    check("informa el pago", s == 302)
    s, _, t = buyer.req("/pedido?c=" + code)
    check("estado: pago informado", "تم الإبلاغ عن الدفع" in t)
    s, _, t = admin.post("/admin?tab=pedidos", {"accion": "pedido_estado", "c": code, "nuevo": "cerrada"}, "/admin?tab=pedidos&c=" + code)
    check("no se puede saltar a 'cerrada'", "no está permitido" in t)
    s, _, t = admin.post("/admin?tab=pedidos", {"accion": "pedido_estado", "c": code, "nuevo": "en_produccion"}, "/admin?tab=pedidos&c=" + code)
    check("no se produce sin seña acreditada", "no está permitido" in t)
    s, _, t = admin.post("/admin?tab=pedidos", {"accion": "pedido_estado", "c": code, "nuevo": "sena_acreditada"}, "/admin?tab=pedidos&c=" + code)
    check("admin acredita la seña", "Seña acreditada" in t)
    for nuevo in ("en_produccion", "embarcada", "cerrada"):
        s, _, t = admin.post("/admin?tab=pedidos", {"accion": "pedido_estado", "c": code, "nuevo": nuevo}, "/admin?tab=pedidos&c=" + code)
    s, _, t = buyer.req("/pedido?c=" + code)
    check("flujo completo hasta 'Cerrada'", "مكتمل" in t)

    other = Client()
    google_login(other, "otro@example.com", sub="sub-other")
    s, h, _ = other.req("/pedido?c=" + code)
    check("otro usuario no ve pedidos ajenos", s == 302)

    print("== límites anti-pedidos falsos")
    for i in range(3):
        buyer.post("/productos", {"producto_id": 2, "kg": 1000, "destino": "AE", "incoterm": "CIF"}, "/productos")
    s, _, t = buyer.post("/productos", {"producto_id": 2, "kg": 1000, "destino": "AE", "incoterm": "CIF"}, "/productos")
    check("máximo 3 pedidos abiertos", "لديك 3 طلبات مفتوحة" in t)

    print("== registros y exportación")
    s, h, t = admin.req("/admin_export?tipo=accesos")
    check("admin exporta CSV de accesos con IP", s == 200 and "text/csv" in h.get("Content-Type", "") and "buyer@example.com" in t and "127.0.0.1" in t or "::1" in t)
    s, _, t = admin.req("/admin?tab=accesos")
    check("registro de accesos muestra OK y FALLÓ", "OK" in t and "FALLÓ" in t)
    s, _, t = admin.req("/admin?tab=resumen")
    check("resumen del admin", s == 200 and "Cuentas" in t)

    print("== cierre de sesión")
    s, h, _ = buyer.req("/logout")
    s, h, _ = buyer.req("/panel")
    check("tras salir, /panel pide ingresar", h.get("Location", "").endswith("/login"))
finally:
    for p in procs:
        p.terminate()
print(f"\n{ok_n} correctas, {fail_n} fallidas")
sys.exit(1 if fail_n else 0)
