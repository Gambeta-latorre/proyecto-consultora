import math
from PIL import Image, ImageDraw, ImageFont
S=4
RED=(208,2,27); BLK=(17,17,17); WHT=(255,255,255)
F="C:/Windows/Fonts/"
def bez(p0,p1,p2,n=60):
    return [((1-t)**2*p0[0]+2*(1-t)*t*p1[0]+t*t*p2[0],(1-t)**2*p0[1]+2*(1-t)*t*p1[1]+t*t*p2[1]) for t in [i/n for i in range(n+1)]]
def icon(d, ox, oy, k=1.0, bg=WHT, fg=BLK):
    def P(x,y): return ((ox+x*k)*S,(oy+y*k)*S)
    def line(a,b,w): d.line([P(*a),P(*b)],fill=fg,width=int(w*k*S))
    def curve(pts,w,col=fg):
        q=[P(*p) for p in pts]; d.line(q,fill=col,width=int(w*k*S),joint="curve")
        for x,y in q[::len(q)-1]: d.ellipse([x-w*k*S/2,y-w*k*S/2,x+w*k*S/2,y+w*k*S/2],fill=col)
    cx,cy,r=150,150,110
    d.ellipse([*P(cx-r,cy-r),*P(cx+r,cy+r)],outline=fg,width=int(10*k*S))
    d.ellipse([*P(cx-46,cy-r),*P(cx+46,cy+r)],outline=fg,width=int(6*k*S))
    line((150,40),(150,260),6); line((40,150),(260,150),6)
    curve(bez((62,95),(150,125),(238,95)),6); curve(bez((62,205),(150,175),(238,205)),6)
    # trayectoria punteada
    for i,(x,y) in enumerate(bez((52,232),(120,205),(205,128),60)):
        if i%4==0:
            X,Y=P(x,y); rr=4*k*S; d.ellipse([X-rr,Y-rr,X+rr,Y+rr],fill=RED)
    # avión
    pts=[(0,-42),(5,-30),(5,-10),(40,12),(40,20),(5,8),(5,24),(16,34),(16,40),(0,36),(-16,40),(-16,34),(-5,24),(-5,8),(-40,20),(-40,12),(-5,-10),(-5,-30)]
    a=math.radians(45); sc=1.05
    tp=[(238+(x*math.cos(a)-y*math.sin(a))*sc, 82+(x*math.sin(a)+y*math.cos(a))*sc) for x,y in pts]
    q=[P(*p) for p in tp]
    d.polygon(q,fill=bg); d.line(q+[q[0]],fill=bg,width=int(9*k*S),joint="curve")
    for x,y in q: d.ellipse([x-4.5*k*S,y-4.5*k*S,x+4.5*k*S,y+4.5*k*S],fill=bg)
    d.polygon(q,fill=RED)
def spaced(d,xy,text,font,fill,sp):
    x,y=xy
    for ch in text:
        d.text((x,y),ch,font=font,fill=fill,anchor="ls"); x+=font.getlength(ch)+sp*S
    return x
def logo_full(path,bg=WHT,fg=BLK,W=1060,H=320):
    im=Image.new("RGB",(W*S,H*S),bg); d=ImageDraw.Draw(im)
    icon(d,20,10,1.0,bg,fg)
    fb=ImageFont.truetype(F+"ariblk.ttf",70*S); fs=ImageFont.truetype(F+"arialbd.ttf",30*S)
    x=spaced(d,(345*S,165*S),"RUMBO ",fb,fg,2); spaced(d,(x,165*S),"GLOBAL",fb,RED,2)
    spaced(d,(350*S,228*S),"CONSULTORES S.A.",fs,fg,11)
    im=im.resize((W*2,H*2),Image.LANCZOS); im.save(path); return im
def logo_icon(path,bg=WHT,fg=BLK,size=300):
    im=Image.new("RGB",(size*S,size*S),bg); d=ImageDraw.Draw(im)
    icon(d,0,0,size/300*0.95,bg,fg)
    im=im.resize((size*2,size*2),Image.LANCZOS); im.save(path); return im
if __name__=="__main__":
    logo_full("01_Logo/logo_consultora.png")
    logo_full("01_Logo/logo_consultora_negativo.png",bg=BLK,fg=WHT)
    logo_icon("01_Logo/isotipo_consultora.png")
    logo_icon("_build/isotipo_dark.png",bg=BLK,fg=WHT)
    print("ok")
