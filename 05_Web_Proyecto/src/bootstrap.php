<?php
declare(strict_types=1);
// Arranque del sitio ASHAB: configuración, núcleo, sesión, idioma y helpers del negocio.

define('SITE_NAME', 'ASHAB');
define('IDIOMAS', ['es' => 'Español', 'en' => 'English', 'ar' => 'العربية']);
define('WHATSAPP_NUMBER', env_default('WHATSAPP_NUMBER', '5491133486017'));  // 54 9 11 3348-6017
define('MAX_ABIERTOS', 3);
define('MIN_KG', 1000);
define('MAX_KG', 48000);
define('PROFORMA_DIAS', 5);
define('PAISES', ['SY', 'LB', 'JO', 'AE', 'OTRO']);

function env_default(string $k, string $d): string
{
    $v = getenv($k);
    return ($v === false || $v === '') ? $d : $v;
}

require __DIR__ . '/core.php';
require __DIR__ . '/lang.php';

send_security_headers();
if (defined('NO_SESSION')) {          // /install corre antes de que existan las tablas
    $_SESSION = [];
    $LANG = 'es';
} else {
    start_session('ASHABSID');
    $LANG = idioma_inicial();
}

/* ---------------------------------------------------------------- Helpers de negocio */

function wa_display(): string
{
    // 5491133486017 -> +54 9 11 3348-6017
    return '+54 9 11 3348-6017';
}

function whatsapp_url(string $msg = ''): string
{
    return 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($msg !== '' ? $msg : t('wa_default'));
}

function url_idioma(string $l): string
{
    $q = $_GET;
    $q['lang'] = $l;
    return '/' . ($GLOBALS['PAGE'] === 'index' ? '' : $GLOBALS['PAGE']) . '?' . http_build_query($q);
}

function nombre_pais(string $c): string
{
    return ['SY' => t('m_sy'), 'LB' => t('m_lb'), 'JO' => t('m_jo'), 'AE' => t('m_ae'), 'OTRO' => t('r_other')][$c] ?? $c;
}

function campo_idioma(array $row, string $base): string
{
    return (string)($row[$base . '_' . lang()] ?? $row[$base . '_es'] ?? '');
}

function normalizar_whatsapp(string $s): ?string
{
    $s = preg_replace('/[\s().\-]/', '', $s);
    $s = preg_replace('/^00/', '+', $s);
    return preg_match('/^\+[1-9]\d{7,14}$/', $s) ? $s : null;
}

function usd(float|string $n): string
{
    return 'USD ' . number_format((float)$n, 2, '.', ',');
}

/* ---------------------------------------------------------------- Usuarios */

function usuario_actual(): ?array
{
    static $cache = false;
    if ($cache !== false) {
        return $cache;
    }
    $cache = null;
    if (!empty($_SESSION['uid'])) {
        $st = db()->prepare('SELECT * FROM usuarios WHERE id = ? AND activo = 1');
        $st->execute([$_SESSION['uid']]);
        $u = $st->fetch() ?: null;
        if ($u) {
            $u['es_admin'] = !empty($u['google_sub']) && email_es_admin($u['email']);
            if ($u['es_admin']) {
                $u['cuenta'] = 'aprobada';
            }
            $cache = $u;
        }
    }
    return $cache;
}

function requerir_login(): array
{
    $u = usuario_actual();
    if (!$u) {
        flash('need_login', 'error');
        redirect('/login');
    }
    return $u;
}

function requerir_perfil(): array
{
    $u = requerir_login();
    if (!$u['es_admin'] && !(int)$u['perfil_completo']) {
        redirect('/perfil');
    }
    return $u;
}

function requerir_admin(): array
{
    $u = requerir_login();
    if (!$u['es_admin']) {
        http_response_code(403);
        exit('403');
    }
    return $u;
}

/** Crea o actualiza el usuario a partir de los datos verificados por Google. */
function usuario_desde_google(array $claims): array
{
    $email = strtolower((string)$claims['email']);
    $sub = (string)$claims['sub'];
    $st = db()->prepare('SELECT * FROM usuarios WHERE google_sub = ? OR email = ?');
    $st->execute([$sub, $email]);
    $u = $st->fetch();
    if ($u) {
        db()->prepare('UPDATE usuarios SET google_sub = ?, nombre = ?, foto_url = ?, ultimo_login = ? WHERE id = ?')
            ->execute([$sub, mb_substr((string)($claims['name'] ?? $u['nombre']), 0, 120), mb_substr((string)($claims['picture'] ?? ''), 0, 300), now(), $u['id']]);
        return $u;
    }
    db()->prepare('INSERT INTO usuarios (email, google_sub, nombre, foto_url, idioma, cuenta, activo, perfil_completo, creado_en, ultimo_login) VALUES (?,?,?,?,?,?,?,?,?,?)')
        ->execute([$email, $sub, mb_substr((string)($claims['name'] ?? ''), 0, 120), mb_substr((string)($claims['picture'] ?? ''), 0, 300), lang(), 'pendiente', 1, 0, now(), now()]);
    $st = db()->prepare('SELECT * FROM usuarios WHERE google_sub = ?');
    $st->execute([$sub]);
    return $st->fetch();
}

function clave_registro(string $pais): string
{
    return in_array($pais, PAISES, true) ? 'reg_' . $pais : 'reg_OTRO';
}

/* ---------------------------------------------------------------- Pedidos y seña del 50 % */

const ESTADOS_ABIERTOS = ['solicitada', 'proforma_emitida', 'sena_informada'];

function pedido_codigo(): string
{
    $abc = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $c = '';
    for ($i = 0; $i < 6; $i++) {
        $c .= $abc[random_int(0, strlen($abc) - 1)];
    }
    return 'ASH-' . $c;
}

function pedidos_abiertos(int $uid): int
{
    $in = implode(',', array_fill(0, count(ESTADOS_ABIERTOS), '?'));
    $st = db()->prepare("SELECT COUNT(*) FROM pedidos WHERE usuario_id = ? AND estado IN ($in)");
    $st->execute(array_merge([$uid], ESTADOS_ABIERTOS));
    return (int)$st->fetchColumn();
}

/** Marca como vencida la proforma cuyo plazo terminó sin que se informara el pago. */
function vencer_proformas(): void
{
    db()->prepare("UPDATE pedidos SET estado = 'vencida', actualizado_en = ? WHERE estado = 'proforma_emitida' AND vence_en IS NOT NULL AND vence_en < ?")
        ->execute([now(), now()]);
}

function cargar_pedido(string $codigo, ?int $uid = null): ?array
{
    vencer_proformas();
    $sql = 'SELECT p.*, pr.nombre_es, pr.nombre_en, pr.nombre_ar, pr.presentacion, u.email AS u_email, u.empresa AS u_empresa, u.nombre AS u_nombre, u.whatsapp AS u_whatsapp
            FROM pedidos p JOIN productos pr ON pr.id = p.producto_id JOIN usuarios u ON u.id = p.usuario_id WHERE p.codigo = ?';
    $args = [$codigo];
    if ($uid !== null) {
        $sql .= ' AND p.usuario_id = ?';
        $args[] = $uid;
    }
    $st = db()->prepare($sql);
    $st->execute($args);
    return $st->fetch() ?: null;
}

/** Calcula total y seña (50 %) en centavos para evitar errores de redondeo. */
function calcular_totales(int $kg, float $precioKg): array
{
    $totalCent = (int)round($kg * $precioKg * 100);
    $senaCent = intdiv($totalCent + 1, 2);
    return [$totalCent / 100, $senaCent / 100, ($totalCent - $senaCent) / 100];
}

function info_bancaria(): string
{
    return trim(str_replace('\n', "\n", (string)env('BANK_INFO', '')));
}

/* ---------------------------------------------------------------- Instalación inicial */

function sembrar_productos(): int
{
    if ((int)db()->query('SELECT COUNT(*) FROM productos')->fetchColumn() > 0) {
        return 0;
    }
    $rows = [
        ['ASHAB Tradicional', 'ASHAB Traditional', 'أعشاب التقليدية', 'Yerba mate con palo, sabor intenso y clásico, el favorito del mercado levantino.', 'Yerba mate with stem, intense classic taste, a favorite in the Levant.', 'متة بالعيدان بنكهة قوية وكلاسيكية، المفضلة في بلاد الشام.', 'Bolsa 1 kg / caja 10 kg', 2.10],
        ['ASHAB Suave', 'ASHAB Smooth', 'أعشاب الناعمة', 'Yerba mate con bajo contenido de palo, sabor más suave y molienda fina.', 'Low-stem yerba mate, smoother flavor and fine grind.', 'متة بعيدان أقل ونكهة ألطف وطحن ناعم.', 'Bolsa 500 g / caja 10 kg', 2.30],
        ['ASHAB con Menta', 'ASHAB with Mint', 'أعشاب بالنعناع', 'Yerba mate con menta natural, fresca y aromática.', 'Yerba mate with natural mint, fresh and aromatic.', 'متة مع النعناع الطبيعي، منعشة وعطرية.', 'Bolsa 500 g / caja 10 kg', 2.40],
        ['ASHAB Premium', 'ASHAB Premium', 'أعشاب الفاخرة', 'Selección de hoja con estacionamiento extendido, para consumidores exigentes.', 'Leaf selection with extended aging, for demanding consumers.', 'اختيار أوراق مع تعتيق ممتد للمستهلك المتطلب.', 'Bolsa 1 kg / caja 12 kg', 2.60],
    ];
    $st = db()->prepare('INSERT INTO productos (nombre_es, nombre_en, nombre_ar, descripcion_es, descripcion_en, descripcion_ar, presentacion, precio_fob_kg, activo) VALUES (?,?,?,?,?,?,?,?,1)');
    foreach ($rows as $r) {
        $st->execute($r);
    }
    return count($rows);
}
