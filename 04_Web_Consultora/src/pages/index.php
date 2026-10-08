<?php $titulo = 'Inicio'; require __DIR__ . '/../layout/header.php'; ?>
<section class="hero">
  <div class="wrap">
    <p class="eyebrow">Consultoría en comercio exterior</p>
    <h1>Llevamos a las pymes argentinas <span class="red">al mundo</span>.</h1>
    <p class="lead">Diagnosticamos el mercado, armamos el plan, resolvemos la documentación y acompañamos cada embarque hasta el cobro.</p>
    <p><a class="btn" href="/contacto">Quiero exportar</a> <a class="btn ghost" href="/servicios">Ver servicios</a></p>
  </div>
</section>

<section class="wrap stats">
  <div><b>4</b><span>países objetivo en Medio Oriente</span></div>
  <div><b>8</b><span>etapas en nuestro ciclo de trabajo</span></div>
  <div><b>3</b><span>idiomas de trabajo: español, inglés y árabe</span></div>
</section>

<section class="wrap">
  <h2>Qué hacemos</h2>
  <div class="grid3">
    <article class="card"><h3>Estudio de mercado</h3><p>Elegimos países y canales según demanda real, aranceles y requisitos sanitarios.</p></article>
    <article class="card"><h3>Habilitaciones y documentos</h3><p>Registro de exportador, marca, certificados de origen y sanitarios, permiso de embarque.</p></article>
    <article class="card"><h3>Logística y cobranza</h3><p>Contenedor, seguro, despacho y cobro asegurado con seña y carta de crédito.</p></article>
  </div>
</section>

<section class="wrap case">
  <div>
    <p class="eyebrow">Caso en curso</p>
    <h2>ASHAB · Yerba mate argentina para Medio Oriente</h2>
    <p>Acompañamos a una yerbatera de Misiones para llegar a Siria, Líbano, Jordania y Emiratos Árabes Unidos, con sitio web en español, inglés y árabe y una política de seña del 50 % para asegurar cada pedido.</p>
    <p><a class="btn" href="<?= h(PROYECTO_URL) ?>">Ver el proyecto</a></p>
  </div>
</section>
<?php require __DIR__ . '/../layout/footer.php'; ?>
