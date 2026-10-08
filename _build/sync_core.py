"""Copia _shared/core.php a cada sitio (cada proyecto de Vercel debe ser autónomo)."""
import shutil
for site in ("04_Web_Consultora", "05_Web_Proyecto"):
    shutil.copy("_shared/core.php", f"{site}/src/core.php")
    print("core.php ->", site)
