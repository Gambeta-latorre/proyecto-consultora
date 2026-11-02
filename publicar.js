// Publica las dos webs en Vercel como proyectos NUEVOS:
//   04_Web_Consultora -> berasoftsrl     (https://berasoftsrl.vercel.app)
//   05_Web_Proyecto   -> patitasfelices  (https://patitasfelices.vercel.app)
// Uso (en la carpeta del repo, con la sesión de Vercel ya iniciada):  node publicar.js
// Patitas Felices usa la misma base Postgres (Neon) del proyecto viejo "ashab", en su propio esquema "patitas".
const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const correr = (cmd, cwd) => { console.log(`\n> ${cmd}`); execSync(cmd, { cwd, stdio: 'inherit' }); };
const intentar = (cmd, cwd) => { try { correr(cmd, cwd); } catch (e) { /* si ya existe, seguimos */ } };

// 1) Web de Berasoft (no usa base de datos)
const bera = path.join(__dirname, '04_Web_Consultora');
intentar('vercel project add berasoftsrl', bera);
correr('vercel link --yes --project berasoftsrl', bera);
correr('vercel deploy --prod --yes', bera);

// 2) Patitas Felices: primero se copia la dirección de la base del proyecto viejo
const pati = path.join(__dirname, '05_Web_Proyecto');
const archivoEnv = path.join(pati, '.env.ashab');
correr('vercel link --yes --project ashab', pati);
correr('vercel env pull .env.ashab --environment=production --yes', pati);
const env = fs.readFileSync(archivoEnv, 'utf8');
fs.unlinkSync(archivoEnv); // no dejar la contraseña de la base en la compu
const m = env.match(/^DATABASE_URL_UNPOOLED="?([^"\r\n]+)/m) || env.match(/^DATABASE_URL="?([^"\r\n]+)/m);

intentar('vercel project add patitasfelices', pati);
correr('vercel link --yes --project patitasfelices', pati);
if (m && m[1]) {
    intentar('vercel env rm DATABASE_URL production --yes', pati);
    console.log('\n> vercel env add DATABASE_URL production');
    execSync('vercel env add DATABASE_URL production', { cwd: pati, input: m[1], stdio: ['pipe', 'inherit', 'inherit'] });
} else {
    console.log('\nNo se pudo copiar la base. En vercel.com abrí el proyecto "patitasfelices" > Storage > Connect Database,');
    console.log('elegí la base del proyecto ashab y después volvé a correr: cd 05_Web_Proyecto && vercel deploy --prod --yes');
}
correr('vercel deploy --prod --yes', pati);
console.log('\nListo. Berasoft: https://berasoftsrl.vercel.app  ·  Patitas Felices: https://patitasfelices.vercel.app');
