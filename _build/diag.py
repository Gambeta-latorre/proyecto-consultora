import math
from PIL import Image, ImageDraw, ImageFont
F = "C:/Windows/Fonts/"
S = 2
RED = (208, 2, 27)


def font(sz, b=False):
    return ImageFont.truetype(F + ("arialbd.ttf" if b else "arial.ttf"), int(sz * S))


THEMES = {
    "rg": dict(pri=(17, 17, 17), acc=RED, tint=(247, 247, 247), tint2=(234, 234, 234), line=(40, 40, 40), txt=(17, 17, 17), on=(255, 255, 255), dec=(255, 232, 235), lane2=(90, 90, 90)),
    "ash": dict(pri=(31, 77, 58), acc=(201, 162, 39), tint=(246, 250, 247), tint2=(232, 241, 235), line=(31, 77, 58), txt=(20, 40, 30), on=(255, 255, 255), dec=(252, 244, 214), lane2=(58, 110, 86)),
}


def wrap(d, text, f, maxw):
    words = text.split()
    lines = []
    cur = ""
    for w in words:
        t = (cur + " " + w).strip()
        if d.textlength(t, font=f) <= maxw * S or not cur:
            cur = t
        else:
            lines.append(cur)
            cur = w
    if cur:
        lines.append(cur)
    return lines


class Diagram:
    def __init__(s, W, H, theme, title, sub=None, lanes=None, colw=300, hdr=200, top=130, lane_h=250, nodew=250, nodeh=150, fs=24):
        s.W, s.H = W, H
        s.t = THEMES[theme]
        s.title, s.sub = title, sub
        s.lanes = lanes
        s.colw, s.hdr, s.top, s.lane_h = colw, hdr, top, lane_h
        s.nw, s.nh, s.fs = nodew, nodeh, fs
        s.im = Image.new("RGB", (W * S, H * S), (255, 255, 255))
        s.d = ImageDraw.Draw(s.im)
        s.nodes = {}
        s.edges = []
        s._frame()

    def _frame(s):
        d, t = s.d, s.t
        d.rectangle([0, 0, s.W * S, 100 * S], fill=t["pri"])
        d.text((40 * S, 50 * S), s.title, font=font(38, True), fill=t["on"], anchor="lm")
        if s.sub:
            d.text((s.W * S - 40 * S, 50 * S), s.sub, font=font(24), fill=t["on"] if t["acc"] == RED else t["acc"], anchor="rm")
        if s.lanes:
            for i, name in enumerate(s.lanes):
                y0 = s.top + i * s.lane_h
                d.rectangle([0, y0 * S, s.W * S, (y0 + s.lane_h) * S], fill=t["tint"] if i % 2 == 0 else t["tint2"])
                d.rectangle([0, y0 * S, (s.hdr - 20) * S, (y0 + s.lane_h) * S], fill=t["pri"] if i % 2 == 0 else t["lane2"])
                f = font(26, True)
                lines = wrap(d, name, f, s.hdr - 60)
                lh = 34
                y = y0 + s.lane_h / 2 - len(lines) * lh / 2 + lh / 2
                for ln in lines:
                    d.text(((s.hdr - 20) / 2 * S, y * S), ln, font=f, fill=t["on"], anchor="mm")
                    y += lh

    def pos(s, col, lane):
        return (s.hdr + s.colw * (col + 0.5), s.top + s.lane_h * (lane + 0.5))

    def node(s, id, col, lane, text, kind="proc", num=None, xy=None, w=None, h=None):
        cx, cy = xy if xy else s.pos(col, lane)
        w = w or s.nw
        h = h or s.nh
        if kind == "dec":
            w += 40
            h += 20
        s.nodes[id] = (cx, cy, w, h, kind, text, num)

    def _draw_node(s, n):
        d, t = s.d, s.t
        cx, cy, w, h, kind, text, num = n
        x0, y0, x1, y1 = (cx - w / 2) * S, (cy - h / 2) * S, (cx + w / 2) * S, (cy + h / 2) * S
        f = font(s.fs, kind != "proc")
        tw = w - 28
        if kind == "proc":
            d.rounded_rectangle([x0 + 4 * S, y0 + 5 * S, x1 + 4 * S, y1 + 5 * S], radius=16 * S, fill=(214, 214, 214))
            d.rounded_rectangle([x0, y0, x1, y1], radius=16 * S, fill=(255, 255, 255), outline=t["pri"], width=4 * S)
            col = t["txt"]
        elif kind == "term":
            d.rounded_rectangle([x0, y0, x1, y1], radius=(h / 2) * S, fill=t["pri"])
            col = t["on"]
            f = font(s.fs, True)
        else:
            pts = [(cx * S, y0), (x1, cy * S), (cx * S, y1), (x0, cy * S)]
            d.polygon(pts, fill=t["dec"])
            d.line(pts + [pts[0]], fill=t["acc"], width=5 * S, joint="curve")
            col = t["txt"]
            tw = w * 0.60
        lines = wrap(d, text, f, tw)
        lh = s.fs * 1.22
        y = cy - len(lines) * lh / 2 + lh / 2
        for ln in lines:
            d.text((cx * S, y * S), ln, font=f, fill=col, anchor="mm")
            y += lh
        if num is not None:
            r = 26
            px, py = x0 / S + 4, y0 / S + 4
            d.ellipse([(px - r) * S, (py - r) * S, (px + r) * S, (py + r) * S], fill=t["acc"])
            d.text((px * S, py * S), str(num), font=font(28, True), fill=(255, 255, 255) if t["acc"] == RED else t["txt"], anchor="mm")

    def port(s, id, p):
        cx, cy, w, h, *_ = s.nodes[id]
        return {"l": (cx - w / 2, cy), "r": (cx + w / 2, cy), "t": (cx, cy - h / 2), "b": (cx, cy + h / 2)}[p]

    def edge(s, a, pa, b, pb, label=None, via=None, lpos=0.0, dash=False):
        s.edges.append((a, pa, b, pb, label, via, lpos, dash))

    def _draw_edge(s, e):
        d, t = s.d, s.t
        a, pa, b, pb, label, via, lpos, dash = e
        p1, p2 = s.port(a, pa), s.port(b, pb)
        if via:
            pts = [p1] + via + [p2]
        else:
            hz = lambda p: p in "lr"
            if hz(pa) and hz(pb):
                if abs(p1[1] - p2[1]) < 2:
                    pts = [p1, p2]
                else:
                    mx = (p1[0] + p2[0]) / 2
                    pts = [p1, (mx, p1[1]), (mx, p2[1]), p2]
            elif not hz(pa) and not hz(pb):
                if abs(p1[0] - p2[0]) < 2:
                    pts = [p1, p2]
                else:
                    my = (p1[1] + p2[1]) / 2
                    pts = [p1, (p1[0], my), (p2[0], my), p2]
            elif hz(pa):
                pts = [p1, (p2[0], p1[1]), p2]
            else:
                pts = [p1, (p1[0], p2[1]), p2]
        col = t["line"]
        q = [(x * S, y * S) for x, y in pts]
        if dash:
            for i in range(len(q) - 1):
                s._dashed(q[i], q[i + 1], col)
        else:
            d.line(q, fill=col, width=5 * S, joint="curve")
        (x0, y0), (x1, y1) = q[-2], q[-1]
        ang = math.atan2(y1 - y0, x1 - x0)
        L = 24 * S
        d.polygon([(x1, y1), (x1 - L * math.cos(ang - 0.4), y1 - L * math.sin(ang - 0.4)), (x1 - L * math.cos(ang + 0.4), y1 - L * math.sin(ang + 0.4))], fill=col)
        if label:
            (sx, sy), (ex, ey) = pts[0], pts[1]
            if abs(ex - sx) < abs(ey - sy):
                lx, ly = sx + 34, sy + (ey - sy) * 0.5 + lpos
            else:
                lx, ly = sx + (ex - sx) * 0.5 + lpos, sy - 26
            f = font(24, True)
            w = d.textlength(label, font=f) / S
            d.rounded_rectangle([(lx - w / 2 - 10) * S, (ly - 19) * S, (lx + w / 2 + 10) * S, (ly + 19) * S], radius=10 * S, fill=t["acc"] if t["acc"] == RED else t["pri"])
            d.text((lx * S, ly * S), label, font=f, fill=(255, 255, 255), anchor="mm")

    def _dashed(s, a, b, col):
        L = math.dist(a, b)
        n = max(int(L / (18 * S)), 1)
        for i in range(0, n, 2):
            p = (a[0] + (b[0] - a[0]) * i / n, a[1] + (b[1] - a[1]) * i / n)
            q = (a[0] + (b[0] - a[0]) * min(i + 1, n) / n, a[1] + (b[1] - a[1]) * min(i + 1, n) / n)
            s.d.line([p, q], fill=col, width=5 * S)

    def save(s, path):
        for e in s.edges:
            s._draw_edge(e)
        for n in s.nodes.values():
            s._draw_node(n)
        im = s.im.resize((s.W, s.H), Image.LANCZOS)
        im.save(path)
        return path


def simple_grid(path, theme, title, sub, steps, cols=3):
    W = 2200
    rows = math.ceil(len(steps) / cols)
    bw, bh = 560, 250
    gx = 120
    H = 100 + 90 + rows * (bh + 120) + 20
    D = Diagram(W, H, theme, title, sub, fs=30)
    x0 = (W - (cols * bw + (cols - 1) * gx)) / 2
    for i, txt in enumerate(steps):
        r, c = divmod(i, cols)
        cc = c if r % 2 == 0 else cols - 1 - c
        cx = x0 + cc * (bw + gx) + bw / 2
        cy = 100 + 90 + r * (bh + 120) + bh / 2 + 30
        D.node(f"n{i}", 0, 0, txt, "proc", num=i + 1, xy=(cx, cy), w=bw, h=bh)
    for i in range(len(steps) - 1):
        r, c = divmod(i, cols)
        r2, _ = divmod(i + 1, cols)
        if r == r2:
            D.edge(f"n{i}", "r" if r % 2 == 0 else "l", f"n{i+1}", "l" if r % 2 == 0 else "r")
        else:
            D.edge(f"n{i}", "b", f"n{i+1}", "t")
    return D.save(path)


def simple_row(path, theme, title, sub, steps):
    W = 2400
    n = len(steps)
    bw, bh = 430, 300
    gx = (W - 80 - n * bw) / (n - 1)
    D = Diagram(W, 520, theme, title, sub, fs=30)
    for i, txt in enumerate(steps):
        D.node(f"n{i}", 0, 0, txt, "proc", num=i + 1, xy=(40 + bw / 2 + i * (bw + gx), 310), w=bw, h=bh)
        if i:
            D.edge(f"n{i-1}", "r", f"n{i}", "l")
    return D.save(path)


def build_simple():
    O = "03_Diagramas/"
    simple_grid(O + "S1_como_funciona_ashab_simple.png", "ash", "¿Cómo funciona ASHAB? Explicado simple", "De la yerbatera al mate en Medio Oriente", [
        "Un distribuidor árabe pide cotización y paga el 50 % de seña",
        "La yerbatera de Misiones elabora y empaca la yerba ASHAB",
        "Rumbo Global prepara los papeles y el permiso de exportación",
        "Un camión lleva el contenedor al puerto de Buenos Aires",
        "El barco cruza el océano hasta Beirut o Dubái (30 a 40 días)",
        "El distribuidor paga el otro 50 %, recibe la yerba y la reparte"])
    simple_grid(O + "S2_como_se_usa_la_web_simple.png", "ash", "¿Cómo se usa la página web? Explicado simple", "Pasos para una empresa nueva", [
        "Entrás a la web y elegís tu idioma: español, inglés o árabe",
        "Ingresás con tu cuenta de Google (sin contraseña nueva)",
        "Cargás los datos de tu empresa y esperás la aprobación",
        "Ves el catálogo y los precios de la yerba",
        "Pedís cotización y recibís la proforma",
        "Pagás la seña del 50 % y confirmamos tu pedido"])
    simple_row(O + "S3_como_trabaja_la_consultora_simple.png", "rg", "¿Cómo trabaja Rumbo Global? Explicado simple", "En 4 pasos", [
        "Escuchamos qué necesita el cliente", "Analizamos el mercado y los riesgos", "Armamos un plan con fechas y costos", "Ejecutamos, controlamos y avisamos"])
    print("simple ok")


if __name__ == "__main__":
    build_simple()
