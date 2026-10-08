"""Prueba de punta a punta del sitio de la consultora (DNI + Google simulado).
Uso (desde la carpeta del proyecto):  python _build/tests/test_consultora.py
"""
import base64, json, os, re, subprocess, sys, time, urllib.parse, urllib.request, http.cookiejar

sys.stdout.reconfigure(encoding="utf-8")
ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", ".."))
SITE = os.path.join(ROOT, "04_Web_Consultora")
DB = os.path.join(ROOT, "_build", "tests", "consultora_test.sqlite")
PHP = os.environ.get("PHP_BIN", "C:/riquelme/php/php")
APP, MOCK = 8210, 8211
BASE = f"http://localhost:{APP}"
if os.path.exists(DB):
    os.remove(DB)
env = dict(os.environ, DB_DSN="sqlite:" + DB, GOOGLE_CLIENT_ID="test-client", GOOGLE_CLIENT_SECRET="secret",
           GOOGLE_AUTH_URL=f"http://localhost:{MOCK}/auth", GOOGLE_TOKEN_URL=f"http://localhost:{MOCK}/token",
           SETUP_TOKEN="tok123", ADMIN_EMAILS="boss@example.com", APP_URL=BASE)
procs = [
    subprocess.Popen([PHP, "-S", f"localhost:{APP}", "-t", "public", "router.php"], cwd=SITE, env=env, stdout=subprocess.DEVNULL, stderr=open(os.path.join(ROOT, "_build", "tests", "app2.log"), "w")),
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
    def __init__(self):
        self.jar = http.cookiejar.CookieJar()
        self.op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar), NoRedirect)

    def req(self, path, data=None):
        body = urllib.parse.urlencode(data).encode() if data is not None else None
        r = urllib.request.Request(BASE + path, data=body, headers={"User-Agent": "test-agent"})
        try:
            resp = self.op.open(r, timeout=20)
        except urllib.error.HTTPError as e:
            resp = e
        return (resp.status if hasattr(resp, "status") else resp.code), dict(resp.headers), resp.read().decode("utf-8", "replace")

    def csrf(self, path):
        m = re.search(r'name="csrf" value="([^"]+)"', self.req(path)[2])
        return m.group(1) if m else ""

    def post(self, path, data, csrf_from=None):
        return self.req(path, dict(data, csrf=self.csrf(csrf_from or path)))


def b64(d):
    return base64.urlsafe_b64encode(json.dumps(d).encode()).decode().rstrip("=")


def google_login(c, email, name="Jefe", sub="sub-admin"):
    c.csrf("/login")
    s, h, _ = c.post("/google", {}, "/login")
    q = urllib.parse.parse_qs(urllib.parse.urlparse(h.get("Location", "")).query)
    claims = dict(aud="test-client", iss="https://accounts.google.com", exp=int(time.time()) + 3600, nonce=q["nonce"][0], sub=sub,
                  email=email, email_verified=True, given_name=name, family_name="Test")
    return c.req(f"/google_callback?code={b64(claims)}&state={q['state'][0]}")


try:
    print("== instalación y páginas")
    c = Client()
    check("install con token malo da 404", c.req("/install?token=x")[0] == 404)
    s, _, t = c.req("/install?token=tok123")
    check("install crea tablas", s == 200 and "Listo" in t, t)
    for p in ("/", "/servicios", "/nosotros", "/contacto", "/login", "/registro", "/privacidad"):
        check(f"GET {p} = 200", c.req(p)[0] == 200)
    s, _, t = c.req("/contacto")
    check("WhatsApp flotante + número internacional + QR", "wa.me/5491133486017" in t and "+54 9 11 3348-6017" in t and "qr_whatsapp.png" in t)
    check("botón de Google visible", "Continuar con Google" in c.req("/login")[2])

    print("== registro e ingreso con DNI")
    u = Client()
    s, _, t = u.post("/registro", {"dni": "12", "nombre": "", "apellido": "", "email": "x", "clave": "123", "clave2": "999"})
    check("registro inválido muestra 5 errores", t.count("alert error") >= 5)
    s, h, _ = u.post("/registro", {"dni": "30.123.456", "nombre": "Ana", "apellido": "Pérez", "email": "ana@test.com", "clave": "ClaveSegura1", "clave2": "ClaveSegura1"})
    check("registro válido (DNI con puntos)", s == 302 and h["Location"].endswith("/login"))
    s, _, t = u.post("/registro", {"dni": "30123456", "nombre": "Ana", "apellido": "Pérez", "email": "otro@test.com", "clave": "ClaveSegura1", "clave2": "ClaveSegura1"})
    check("DNI duplicado rechazado", "Ya existe una cuenta" in t)
    s, _, t = u.post("/login", {"dni": "30123456", "clave": "mala"})
    check("clave incorrecta", "DNI o contraseña incorrectos" in t)
    s, _, t = u.post("/login", {"dni": "99999999", "clave": "mala"})
    check("DNI inexistente da el mismo mensaje", "DNI o contraseña incorrectos" in t)
    s, h, _ = u.post("/login", {"dni": "30123456", "clave": "ClaveSegura1"})
    check("ingreso correcto va al panel", s == 302 and h["Location"].endswith("/panel"))
    s, _, t = u.req("/panel")
    check("panel muestra DNI y nombre", "Hola, Ana" in t and "DNI 30123456" in t)
    s, _, t = u.req("/admin")
    check("cliente no entra a /admin (403)", s == 403)
    u.req("/logout")
    check("tras salir, /panel redirige", u.req("/panel")[1].get("Location", "").endswith("/login"))

    print("== bloqueo por intentos")
    v = Client()
    for i in range(5):
        v.post("/login", {"dni": "30123456", "clave": "mala"})
    s, _, t = v.post("/login", {"dni": "30123456", "clave": "ClaveSegura1"})
    check("cuenta bloqueada tras 5 fallos (aun con clave buena)", "bloqueada temporalmente" in t)

    print("== administrador con Google")
    a = Client()
    s, h, _ = google_login(a, "boss@example.com")
    check("admin entra a /admin con Google", s == 302 and h["Location"].endswith("/admin"), h)
    s, _, t = a.req("/admin?tab=usuarios")
    check("admin ve DNI y datos de los clientes", "30123456" in t and "ana@test.com" in t and "boss@example.com" in t)
    check("admin ve que la cuenta está bloqueada", "bloqueado hasta" in t)
    s, _, t = a.req("/admin?tab=accesos")
    check("registro de accesos: éxitos, fallos, IP, método", "FALLÓ" in t and "OK" in t and "clave incorrecta" in t and "google" in t and "127.0.0.1" in t or "::1" in t)
    rows = re.findall(r'30123456.*?name="id" value="(\d+)"', a.req("/admin?tab=usuarios")[2], re.S)
    uid = rows[0]
    s, _, t = a.post("/admin?tab=usuarios", {"accion": "user_activo", "id": uid, "activo": "1"}, "/admin?tab=usuarios")
    check("admin desbloquea / reactiva", "Usuario actualizado" in t)
    s, _, t = a.post("/admin?tab=proyectos", {"accion": "proyecto_nuevo", "usuario_id": uid, "nombre": "Proyecto ASHAB", "estado": "En plan", "avance": "30"}, "/admin?tab=proyectos")
    check("admin crea proyecto", "Proyecto creado" in t)
    s, h, t = a.req("/admin_export?tipo=usuarios")
    check("exporta usuarios en CSV con DNI", s == 200 and "text/csv" in h.get("Content-Type", "") and "30123456" in t)
    u2 = Client()
    u2.post("/login", {"dni": "30123456", "clave": "ClaveSegura1"})
    check("el cliente ve su proyecto", "Proyecto ASHAB" in u2.req("/panel")[2] and "30%" in u2.req("/panel")[2])
    s, _, t = u2.post("/contacto", {"nombre": "Ana", "email": "ana@test.com", "telefono": "", "mensaje": "Quiero exportar yerba a Líbano"})
    check("formulario de contacto guarda la consulta", s == 302)
    check("admin ve la consulta", "Quiero exportar yerba" in a.req("/admin?tab=consultas")[2])
finally:
    for p in procs:
        p.terminate()
print(f"\n{ok_n} correctas, {fail_n} fallidas")
sys.exit(1 if fail_n else 0)
